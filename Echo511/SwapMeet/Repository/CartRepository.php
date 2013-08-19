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
 * @author Nikolas Tsiongas
 */
class CartRepository extends Repository
{

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



	public function getRecent()
	{
		$rows = $this->connection->select('*')
			->from($this->getTable())
			->where('[lastCheckIn] > ?', $this->getRecentLimitDatetime())
			->fetchAll();

		return $this->createEntities($rows);
	}



	public function isRecent(Cart $cart)
	{
		if ($cart->lastCheckIn > $this->getRecentLimitDatetime()) {
			return true;
		}
		return false;
	}



	private function getRecentLimitDatetime()
	{
		$datetime = new DateTime;
		$datetime->setTimestamp(time() - (500));
		return $datetime;
	}



}