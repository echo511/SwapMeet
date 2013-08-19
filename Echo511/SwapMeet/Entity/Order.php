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
use LeanMapper\Entity;


/**
 * Order.
 * 
 * @property int $id
 * @property string $buyersEmail
 * @property DateTime $created
 * @property User $user m:hasOne
 * @property Item[] $items m:hasMany
 * 
 * @author Nikolas Tsiongas
 */
class Order extends Entity
{

	public function initDefaults()
	{
		$this->created = new DateTime;
	}



}