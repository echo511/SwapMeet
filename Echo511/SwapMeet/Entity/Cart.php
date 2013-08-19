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

use LeanMapper\Entity;
use DateTime;


/**
 * What has user in cart?
 * 
 * @property int $id
 * @property DateTime $lastCheckIn
 * @property User|null $user m:hasOne
 * @property Item[] $items m:hasMany
 */


/**
 * @author Nikolas Tsiongas
 */
class Cart extends Entity
{

	public function initDefaults()
	{
		$this->lastCheckIn = new DateTime;
	}



	public function getTotalPrice()
	{
		$price = 0;
		foreach ($this->items as $item) {
			$price = $price + $item->price;
		}
		return $price;
	}



	public function getTotalItems()
	{
		return count($this->items);
	}



}