<?php

namespace Echo511\SwapMeet\Repository;

use Echo511\SwapMeet\Entity\User;
use Echo511\SwapMeet\ORM\Repository;


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