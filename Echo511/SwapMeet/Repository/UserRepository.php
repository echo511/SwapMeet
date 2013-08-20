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

use Echo511\SwapMeet\ORM\Repository;


/**
 * User repository.
 * 
 * @author Nikolas Tsiongas
 */
class UserRepository extends Repository
{

	/**
	 * Find user by his credentials.
	 * @param string $username
	 * @param string $password
	 * @return boolean
	 */
	public function findByCredentials($username, $password)
	{
		$row = $this->connection->select('*')
			->from($this->getTable())
			->where('[username] = ? AND [password] = ?', $username, $this->hashPassword($password))
			->fetch();

		if (!$row) {
			return false;
		}

		return $this->createEntity($row);
	}



	/**
	 * Hash user's password.
	 * @param string $password
	 * @return string
	 */
	protected function hashPassword($password)
	{
		return sha1($password);
	}



}