<?php

/**
 * This file is part of SwapMeet.
 *
 * Copyright (c) 2013 Nikolas Tsiongas (http://congi.cz)
 *
 * For the full copyright and license information, please view
 * the file license.txt that was distributed with this source code.
 */

namespace Echo511\SwapMeet\Presenter;

use Echo511\SwapMeet\Entity\Customer;
use Echo511\SwapMeet\Entity\Shop;
use Nette\Application\UI\Presenter;


abstract class BasePresenter extends Presenter
{

	/** @var Customer */
	protected $customer;

	/** @var Shop */
	protected $shop;


	public function injectBasePresenter(Customer $customer, Shop $shop)
	{
		$this->customer = $customer;
		$this->shop = $shop;
	}



	public function startup()
	{
		parent::startup();
		$this->customer->checkIn($this->shop);
		$this->template->cart = $this->customer->getCart();
	}



}