<?php

/**
 * This file is part of SwapMeet.
 *
 * Copyright (c) 2013 Nikolas Tsiongas (http://congi.cz)
 *
 * For the full copyright and license information, please view
 * the file license.txt that was distributed with this source code.
 */

namespace Echo511\SwapMeet\Service;

use Echo511\SwapMeet\Entity\Order;
use Echo511\SwapMeet\Entity\User;
use Echo511\SwapMeet\Repository\ItemRepository;
use Echo511\SwapMeet\Repository\OrderRepository;
use Echo511\SwapMeet\Transactions\Transactions;
use Nette\Object;


/**
 * Cancel order scenarios.
 * 
 * @author Nikolas Tsiongas
 */
class CancelOrder extends Object
{

	/** @var string */
	private $salt;

	/** @var ItemRepository */
	private $itemRepository;

	/** @var OrderRepository */
	private $orderRepository;

	/** @var Transactions */
	private $transactions;


	/**
	 * @param string $salt
	 * @param ItemRepository $itemRepository
	 * @param OrderRepository $orderRepository
	 * @param Transactions $transactions
	 */
	public function __construct($salt, ItemRepository $itemRepository, OrderRepository $orderRepository, Transactions $transactions)
	{
		$this->salt = $salt;
		$this->itemRepository = $itemRepository;
		$this->orderRepository = $orderRepository;
		$this->transactions = $transactions;
	}



	/**
	 * Remove items from order owned by seller.
	 * @param User $seller
	 * @param Order $order
	 */
	public function cancelItemsByUser(User $seller, Order $order)
	{
		$this->transactions->startTransaction(get_called_class() . 'cancelItemsByUser');
		foreach ($order->items as $item) {
			if ($item->user->id == $seller->id) {
				$order->removeFromItems($item);
				$item->remaining = $item->remaining + 1;
				$this->itemRepository->persist($item);
			}
		}

		$this->orderRepository->persist($order);

		if (count($order->items) == 0) {
			$this->cancel($order);
		}

		$this->transactions->endTransaction(get_called_class() . 'cancelItemsByUser');
	}



	/**
	 * Cancel whole order.
	 * @param Order $order
	 */
	public function cancel(Order $order)
	{
		$this->transactions->startTransaction(get_called_class() . 'cancel');
		$this->orderRepository->delete($order);
		$this->transactions->endTransaction(get_called_class() . 'cancel');
	}



	/**
	 * Create manipulation pass for seller & buyer.
	 * @param User $user
	 * @param Order $order
	 * @param string $role
	 * @return string
	 */
	public function createManipulationPass(User $user, Order $order, $role = 'seller')
	{
		return sha1($user->id . $user->username . $user->email . $order->id . $role . $this->salt);
	}



}