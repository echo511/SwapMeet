<?php

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