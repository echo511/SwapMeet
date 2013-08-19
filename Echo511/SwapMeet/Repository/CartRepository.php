<?php

/**
 * This file is part of SwapMeet.
 *
 * Copyright (c) 2013 Nikolas Tsiongas (http://congi.cz)
 *
 * For the full copyright and license information, please view
 * the file license.txt that was distributed with this source code.
 */

namespace Echo511\SwapMeet\Repository;

use DateTime;
use Echo511\SwapMeet\Entity\Cart;
use Echo511\SwapMeet\Entity\User;
use Echo511\SwapMeet\ORM\Repository;


/**
 * Cart repository.
 * 
 * @author Nikolas Tsiongas
 */
class CartRepository extends Repository
{

	/** @var int */
	private $cartLifetime = 300;


	/**
	 * Set cart life time in seconds.
	 * @param int $seconds
	 */
	public function setCartLifetime($seconds)
	{
		$this->cartLifetime = $seconds;
	}



	/**
	 * Return cart by user.
	 * @param User $user
	 * @return Cart|boolean
	 */
	public function getByUser(User $user)
	{
		$row = $this->connection->select('*')
			->from($this->getTable())
			->where('[user_id] = ?', $user->id)
			->fetch();

		if (!$row) {
			return false;
		}

		return $this->createEntity($row);
	}



	/**
	 * Return recent carts.
	 * @return Cart[]
	 */
	public function getRecent()
	{
		$rows = $this->connection->select('*')
			->from($this->getTable())
			->where('[lastCheckIn] > ?', $this->getRecentLimitDatetime())
			->fetchAll();

		return $this->createEntities($rows);
	}



	/**
	 * Is cart recent?
	 * @param Cart $cart
	 * @return boolean
	 */
	public function isRecent(Cart $cart)
	{
		if ($cart->lastCheckIn > $this->getRecentLimitDatetime()) {
			return true;
		}
		return false;
	}



	/**
	 * Return limit datetime for recent carts.
	 * @return DateTime
	 */
	private function getRecentLimitDatetime()
	{
		$datetime = new DateTime;
		$datetime->setTimestamp(time() - $this->cartLifetime);
		return $datetime;
	}



}