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

use DateTime;
use Echo511\SwapMeet\Repository\CartRepository;
use Echo511\SwapMeet\Transactions\Transactions;
use Exception;
use Nette\Object;


/**
 * @author Nikolas Tsiongas
 */
class Customer extends Object
{

	/** @var Cart */
	private $cart;

	/** @var CartRepository */
	private $cartRepository;

	/** @var User */
	private $user;

	/** @var Transactions */
	private $transactions;


	public function __construct(Cart $cart, CartRepository $cartRepository, Transactions $transactions)
	{
		$this->cart = $cart;
		$this->cartRepository = $cartRepository;
		$this->transactions = $transactions;
	}



	public function setUser(User $user)
	{
		$this->user = $user;
	}



	public function getUser()
	{
		return $this->user;
	}



	public function checkIn(Shop $shop)
	{
		$this->transactions->startTransaction(get_called_class() . 'checkIn');
		$cart = $this->cart;
		if ($this->cartRepository->isRecent($cart)) {
			$cart->lastCheckIn = new DateTime;
			$this->cartRepository->persist($cart);
		} else {
			$items = $cart->items;
			foreach ($items as $item) {
				$cart->removeFromItems($item);
				try {
					$this->requestItem($item, $shop);
				} catch (Exception $e) {
					
				}
			}
			$cart->lastCheckIn = new DateTime;
			$this->cartRepository->persist($cart);
		}
		$this->transactions->endTransaction(get_called_class() . 'checkIn');
	}



	public function getCart()
	{
		return $this->cart;
	}



	public function requestItem(Item $item, Shop $shop)
	{
		$shop->giveItem($item, $this);
	}



	public function takeItem(Item $item)
	{
		$this->transactions->startTransaction(get_called_class() . 'takeItem');
		$this->cart->addToItems($item);
		$this->cartRepository->persist($this->cart);
		$this->transactions->endTransaction(get_called_class() . 'takeItem');
	}



	public function returnItem(Item $item, Shop $shop)
	{
		$this->transactions->startTransaction(get_called_class() . 'returnItem');
		$this->cart->removeFromItems($item);
		$this->cartRepository->persist($this->cart);
		$this->transactions->endTransaction(get_called_class() . 'returnItem');
	}



	public function submitOrder(Order $order, Shop $shop)
	{
		$shop->processOrder($order, $this->getCart(), $this);
	}



	public function takeOrder(Order $order)
	{
		$this->transactions->startTransaction(get_called_class() . 'takeOrder');
		foreach ($order->items as $item) {
			$this->cart->removeFromItems($item);
		}
		$this->cartRepository->persist($this->cart);
		$this->transactions->endTransaction(get_called_class() . 'takeOrder');
	}



}