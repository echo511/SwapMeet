<?php

/**
 * This file is part of SwapMeet.
 *
 * Copyright (c) 2013 Nikolas Tsiongas (http://congi.cz)
 *
 * For the full copyright and license information, please view
 * the file license.txt that was distributed with this source code.
 */

namespace Echo511\SwapMeet\Entity;

use Echo511\SwapMeet\Repository\ImageRepository;
use Echo511\SwapMeet\Repository\ItemRepository;
use Echo511\SwapMeet\Repository\OrderRepository;
use Echo511\SwapMeet\Service\AvailabilityService;
use Echo511\SwapMeet\Transactions\Transactions;
use Exception;
use Nette\Object;


/**
 * Representation of shop. Is virtual - not stored in database.
 * 
 * @author Nikolas Tsiongas
 */
class Shop extends Object
{

	/** @var callable */
	public $onSuccessOrder;

	/** @var AvailabilityService */
	private $availabilityService;

	/** @var ImageRepository */
	private $imageRepository;

	/** @var ItemRepository */
	private $itemRepository;

	/** @var OrderRepository */
	private $orderRepository;

	/** @var Transactions */
	private $transactions;


	/**
	 * @param AvailabilityService $availabilityService
	 * @param ItemRepository $itemRepository
	 * @param OrderRepository $orderRepository
	 * @param Transactions $transactions
	 */
	public function __construct(AvailabilityService $availabilityService, ImageRepository $imageRepository, ItemRepository $itemRepository, OrderRepository $orderRepository, Transactions $transactions)
	{
		$this->availabilityService = $availabilityService;
		$this->imageRepository = $imageRepository;
		$this->itemRepository = $itemRepository;
		$this->orderRepository = $orderRepository;
		$this->transactions = $transactions;
	}



	/**
	 * Give item to customer if available.
	 * @param Item $item
	 * @param Customer $customer
	 */
	public function giveItem(Item $item, Customer $customer)
	{
		$this->checkAvailability($item);
		$customer->takeItem($item);
	}



	/**
	 * Add item into shop.
	 * @param Item $item
	 * @param array $images
	 */
	public function addItem(Item $item, array $images = array())
	{
		$this->transactions->startTransaction(get_called_class() . 'addItem');
		$this->itemRepository->persist($item);
		foreach ($images as $image) {
			$this->imageRepository->persist($image, $item);
		}
		$this->transactions->endTransaction(get_called_class() . 'addItem');
	}



	/**
	 * Process submitted order and give it back to customer.
	 * @param Order $order
	 * @param Cart $cart
	 * @param Customer $customer
	 */
	public function processOrder(Order $order, Cart $cart, Customer $customer)
	{
		$this->transactions->startTransaction(get_called_class() . 'processOrder');
		$this->orderRepository->persist($order);

		foreach ($cart->items as $item) {
			$order->addToItems($item);
			$item->remaining = $item->remaining - 1;
			$this->itemRepository->persist($item);
		}

		$user = $customer->getUser();
		if ($user) {
			$order->user = $user;
		}

		$this->orderRepository->persist($order);
		$customer->takeOrder($order);

		// Success
		$this->onSuccessOrder($order, $customer);
		$this->transactions->endTransaction(get_called_class() . 'processOrder');
	}



	/**
	 * Check if item is available.
	 * @param Item $item
	 * @throws Exception
	 */
	protected function checkAvailability(Item $item)
	{
		$title = $item->title;
		$remaining = $item->remaining;
		$available = $this->availabilityService->countAvailability($item);

		if ($remaining > 0 && $available < 1) {
			throw new Exception("Item $title was picked by someone before you. Wait if they change their decision.");
		} elseif ($remaining < 0) {
			throw new Exception("Item $title is not available.");
		}
	}



}