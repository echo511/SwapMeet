<?php

/**
 * This file is part of SwapMeet.
 *
 * Copyright (c) 2013 Nikolas Tsiongas (http://congi.cz)
 *
 * For the full copyright and license information, please view
 * the file license.txt that was distributed with this source code.
 */

namespace Echo511\SwapMeet\Security\Authenticator;

use Echo511\Security\AuthenticationException;
use Echo511\Security\IAuthenticator;
use Echo511\Security\IAuthenticatorParameters;
use Echo511\SwapMeet\Entity\User;
use Echo511\SwapMeet\Repository\UserRepository;
use Nette\Object;


/**
 * Database authenticator method.
 * 
 * @author Nikolas Tsiongas
 */
class DatabaseAuthenticator extends Object implements IAuthenticator
{

	/** @var UserRepository */
	private $userRepository;


	/**
	 * @param UserRepository $userRepository
	 */
	public function __construct(UserRepository $userRepository)
	{
		$this->userRepository = $userRepository;
	}



	/* -------- IAuthenticator -------- */

	public function authenticate(IAuthenticatorParameters $parameters = null)
	{
		$response = $this->userRepository->findByCredentials($parameters->username, $parameters->password);

		if ($response instanceof User) {
			return $response;
		} else {
			throw new AuthenticationException('Invalid login credentials.');
		}
	}



}