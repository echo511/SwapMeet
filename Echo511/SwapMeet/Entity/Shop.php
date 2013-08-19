<?php

namespace Echo511\SwapMeet\Entity;

use Echo511\SwapMeet\Repository\ItemRepository;
use Echo511\SwapMeet\Repository\OrderRepository;
use Echo511\SwapMeet\Service\AvailabilityService;
use Echo511\SwapMeet\Transactions\Transactions;
use Exception;
use Nette\Object;


class Shop extends Object
{

	/** @var callable */
	public $onSuccessOrder;

	/** @var AvailabilityService */
	private $availabilityService;

	/** @var ItemRepository */
	private $itemRepository;

	/** @var OrderRepository */
	private $orderRepository;

	/** @var Transactions */
	private $transactions;


	public function __construct(AvailabilityService $availabilityService, ItemRepository $itemRepository, OrderRepository $orderRepository, Transactions $transactions)
	{
		$this->availabilityService = $availabilityService;
		$this->itemRepository = $itemRepository;
		$this->orderRepository = $orderRepository;
		$this->transactions = $transactions;
	}



	public function giveItem(Item $item, Customer $customer)
	{
		$this->checkAvailability($item);
		$customer->takeItem($item);
	}



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