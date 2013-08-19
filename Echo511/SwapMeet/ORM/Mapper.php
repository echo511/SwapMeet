<?php

/**
 * This file is part of SwapMeet.
 *
 * Copyright (c) 2013 Nikolas Tsiongas (http://congi.cz)
 *
 * For the full copyright and license information, please view
 * the file license.txt that was distributed with this source code.
 */

namespace Echo511\SwapMeet\ORM;

use LeanMapper\DefaultMapper;
use LeanMapper\Row;


/**
 * ORM mapper.
 * 
 * @author Nikolas Tsiongas
 */
class Mapper extends DefaultMapper
{

	public function getEntityClass($table, Row $row = null)
	{
		return 'Echo511\SwapMeet\Entity\\' . ucfirst($table);
	}



}