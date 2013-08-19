<?php

namespace Echo511\SwapMeet\Entity;

use DateTime;
use LeanMapper\Entity;


/**
 * Order entity.
 * 
 * @author Nikolas Tsiongas
 * 
 * @property int $id
 * @property string $buyersEmail
 * @property DateTime $created
 * @property User $user m:hasOne
 * @property Item[] $items m:hasMany
 */
class Order extends Entity
{

	public function initDefaults()
	{
		$this->created = new DateTime;
	}



}