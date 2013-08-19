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
 * Representation of customer. Is virtual - not stored in database.
 * 
 * @property Cart $cart
 * @property User $user
 * 
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


	/**
	 * @param Cart $cart
	 * @param CartRepository $cartRepository
	 * @param Transactions $transactions
	 */
	public function __construct(Cart $cart, CartRepository $cartRepository, Transactions $transactions)
	{
		$this->cart = $cart;
		$this->cartRepository = $cartRepository;
		$this->transactions = $transactions;
	}



	/**
	 * Return customer's cart.
	 * @return Cart
	 */
	public function getCart()
	{
		return $this->cart;
	}



	/**
	 * Bind user to customer.
	 * @param User $user
	 */
	public function setUser(User $user)
	{
		$this->user = $user;
	}



	/**
	 * Return binded user.
	 * @return User|null
	 */
	public function getUser()
	{
		return $this->user;
	}



	/**
	 * Mark customer as active.
	 * @param Shop $shop
	 */
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



	/**
	 * Ask shop for item.
	 * @param Item $item
	 * @param Shop $shop
	 */
	public function requestItem(Item $item, Shop $shop)
	{
		$shop->giveItem($item, $this);
	}



	/**
	 * Take item from shop. (Do NOT call directly.)
	 * @param Item $item
	 */
	public function takeItem(Item $item)
	{
		$this->transactions->startTransaction(get_called_class() . 'takeItem');
		$this->cart->addToItems($item);
		$this->cartRepository->persist($this->cart);
		$this->transactions->endTransaction(get_called_class() . 'takeItem');
	}



	/**
	 * Return item to shop.
	 * @param Item $item
	 * @param Shop $shop
	 */
	public function returnItem(Item $item, Shop $shop)
	{
		$this->transactions->startTransaction(get_called_class() . 'returnItem');
		$this->cart->removeFromItems($item);
		$this->cartRepository->persist($this->cart);
		$this->transactions->endTransaction(get_called_class() . 'returnItem');
	}



	/**
	 * Submit order in shop.
	 * @param Order $order
	 * @param Shop $shop
	 */
	public function submitOrder(Order $order, Shop $shop)
	{
		$shop->processOrder($order, $this->getCart(), $this);
	}



	/**
	 * Take processed order from shop. (Do NOT call directly.)
	 * @param Order $order
	 */
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