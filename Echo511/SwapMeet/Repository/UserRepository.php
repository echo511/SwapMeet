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

use Echo511\SwapMeet\Entity\User;
use Echo511\SwapMeet\ORM\Repository;


/**
 * @author Nikolas Tsiongas
 */
class UserRepository extends Repository
{

	public function provideByAnonymId($anonym_id)
	{
		$row = $this->connection->select('*')
			->from($this->getTable())
			->where('[isAnonym] = ?', true)
			->where('[username] = ?', '__anonym_' . $anonym_id)
			->fetch();

		if (!$row) {
			$user = new User;
			$user->username = '__anonym_' . $anonym_id;
			$user->isAnonym = true;
			$this->persist($user);
			return $user;
		}

		return $this->createEntity($row);
	}



}