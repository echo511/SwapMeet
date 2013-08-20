<?php

/**
 * This file is part of SwapMeet.
 *
 * Copyright (c) 2013 Nikolas Tsiongas (http://congi.cz)
 *
 * For the full copyright and license information, please view
 * the file license.txt that was distributed with this source code.
 */

namespace Echo511\SwapMeet\Security\UserStorage;

use Echo511\Security\IUser;
use Echo511\Security\IUserStorage;
use Echo511\SwapMeet\Repository\UserRepository;
use Nette\Http\Session;
use Nette\Http\SessionSection;
use Nette\Object;


/**
 * Stores logged user's id in session and restores user entity from database.
 * 
 * @author Nikolas Tsiongas
 */
final class MySQLUserStorage extends Object implements IUserStorage
{

	/** @var SessionSection */
	private $session;

	/** @var UserRepository */
	private $userRepository;


	/**
	 * @param Session $session
	 * @param UserRepository $userRepository
	 */
	public function __construct(Session $session, UserRepository $userRepository)
	{
		$this->session = $session->getSection(get_called_class());
		$this->userRepository = $userRepository;
	}



	/* -------- IUserStorage -------- */

	public function storeUser(IUser $user)
	{
		$this->session->id = $user->id;
	}



	public function restoreUser()
	{
		if ($this->isStored()) {
			return $this->userRepository->get($this->session->id);
		} else {
			return false;
		}
	}



	public function wipeUser()
	{
		if ($this->isStored()) {
			unset($this->session->id);
		}
	}



	public function isStored()
	{
		return isset($this->session->id);
	}



}