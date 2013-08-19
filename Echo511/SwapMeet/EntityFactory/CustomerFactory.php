<?php

namespace Echo511\SwapMeet\EntityFactory;

use DateTime;
use Echo511\SwapMeet\Entity\Cart;
use Echo511\SwapMeet\Entity\Customer;
use Echo511\SwapMeet\Repository\CartRepository;
use Echo511\SwapMeet\Repository\UserRepository;
use Echo511\SwapMeet\Transactions\Transactions;
use Nette\Http\Session;
use Nette\Http\SessionSection;
use Nette\Security\User as SecurityUser;


class CustomerFactory
{

	/** @var CartRepository */
	private $cartRepository;

	/** @var UserRepository */
	private $userRepository;

	/** @var SecurityUser */
	private $securityUser;

	/** @var SessionSection */
	private $session;

	/** @var Transactions */
	private $transactions;


	public function __construct(CartRepository $cartRepository, UserRepository $userRepository, SecurityUser $securityUser, Session $session, Transactions $transactions)
	{
		$this->cartRepository = $cartRepository;
		$this->userRepository = $userRepository;
		$this->securityUser = $securityUser;
		$this->session = $session->getSection(get_called_class());
		$this->transactions = $transactions;
	}



	public function create()
	{
		$cart = $this->getCart();

		$customer = new Customer($this->getCart(), $this->cartRepository, $this->transactions);
		if ($this->securityUser->isLoggedIn()) {
			$id = $this->securityUser->getId();
			$user = $this->userRepository->get($id);
			$customer->setUser($user);
		}
		return $customer;
	}



	private function getCart()
	{
		$this->transactions->startTransaction(get_called_class() . 'getCart');
		if ($this->securityUser->isLoggedIn()) {
			$user_id = $this->securityUser->getId();
			$user = $this->userRepository->get($user_id);

			$cart = $this->cartRepository->getByUser($user);
			if (!$cart) {
				$cart = new Cart;
				$cart->user = $user;
			}
		} else {
			if (isset($this->session->cart_id)) {
				$id = $this->session->cart_id;
				$cart = $this->cartRepository->get($id);
				if (!$cart) {
					$cart = new Cart;
				}
			} else {
				$cart = new Cart;
			}
		}

		$cart->lastCheckIn = new DateTime;
		$this->cartRepository->persist($cart);
		$this->session->cart_id = $cart->id;
		$this->transactions->endTransaction(get_called_class() . 'getCart');

		return $cart;
	}



}