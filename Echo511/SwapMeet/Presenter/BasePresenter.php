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

use Echo511\Security\CurrentUser;
use Echo511\SwapMeet\Entity\Customer;
use Echo511\SwapMeet\Entity\Shop;
use Nette\Application\UI\Presenter;


/**
 * Abstract presenter.
 * 
 * @property CurrentUser $user
 * 
 * @author Nikolas Tsiongas
 */
abstract class BasePresenter extends Presenter
{

	/** @var Customer */
	protected $customer;

	/** @var Shop */
	protected $shop;

	/** @var CurrentUser */
	private $user;


	/**
	 * Inject dependencies.
	 * @param Customer $customer
	 * @param Shop $shop
	 */
	public function injectBasePresenter(CurrentUser $user, Customer $customer, Shop $shop)
	{
		$this->customer = $customer;
		$this->shop = $shop;
		$this->user = $user;
	}



	/**
	 * Check in customer. Insert cart in template.
	 */
	public function startup()
	{
		parent::startup();
		$this->customer->checkIn($this->shop);
		$this->template->cart = $this->customer->getCart();
	}



	public function createTemplate($class = NULL)
	{
		$template = parent::createTemplate($class);
		$template->registerHelper('gravatar', function ($email, $size = 20) {
				return $this->gravatar($email, $size);
			});
		return $template;
	}



	/**
	 * 
	 * @return CurrentUser
	 */
	public function getUser()
	{
		return $this->user;
	}



	/**
	 * Get either a Gravatar URL or complete image tag for a specified email address.
	 *
	 * @param string $email The email address
	 * @param string $s Size in pixels, defaults to 80px [ 1 - 2048 ]
	 * @param string $d Default imageset to use [ 404 | mm | identicon | monsterid | wavatar ]
	 * @param string $r Maximum rating (inclusive) [ g | pg | r | x ]
	 * @param boole $img True to return a complete IMG tag False for just the URL
	 * @param array $atts Optional, additional key/value attributes to include in the IMG tag
	 * @return String containing either just a URL or a complete image tag
	 * @source http://gravatar.com/site/implement/images/php/
	 */
	protected function gravatar($email, $s = 80, $d = 'mm', $r = 'g', $img = false, $atts = array())
	{
		$url = 'http://www.gravatar.com/avatar/';
		$url .= md5(strtolower(trim($email)));
		$url .= "?s=$s&d=$d&r=$r";
		if ($img) {
			$url = '<img src="' . $url . '"';
			foreach ($atts as $key => $val)
				$url .= ' ' . $key . '="' . $val . '"';
			$url .= ' />';
		}
		return $url;
	}



}