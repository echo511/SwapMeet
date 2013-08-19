<?php

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