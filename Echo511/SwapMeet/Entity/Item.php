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


/**
 * Selling item entity.
 * 
 * @author Nikolas Tsiongas
 * 
 * @property int $id
 * @property string $title
 * @property int $price
 * @property int $remaining
 * @property User $user m:hasOne
 */
class Item extends Entity
{
	
}