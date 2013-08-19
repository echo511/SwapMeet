<?php

namespace Echo511\SwapMeet\Entity;

use LeanMapper\Entity;


/**
 * Tag entity.
 * 
 * @author Nikolas Tsiongas
 * 
 * @property int $id
 * @property string $title
 * @property Item[] $items m:hasMany
 */
class Tag extends Entity
{
	
}