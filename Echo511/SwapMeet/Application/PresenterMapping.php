<?php

/**
 * This file is part of SwapMeet.
 *
 * Copyright (c) 2013 Nikolas Tsiongas (http://congi.cz)
 *
 * For the full copyright and license information, please view
 * the file license.txt that was distributed with this source code.
 */

namespace Echo511\SwapMeet\Application;

use Kdyby\Events\Subscriber;
use Nette\Application\PresenterFactory;
use Nette\Object;


/**
 * Custom presenters' classes.
 * 
 * @author Nikolas Tsiongas
 */
class PresenterMapping extends Object implements Subscriber
{

	/** @var PresenterFactory */
	private $presenterFactory;


	/**
	 * @param PresenterFactory $presenterFactory
	 */
	public function __construct(PresenterFactory $presenterFactory)
	{
		$this->presenterFactory = $presenterFactory;
	}



	/**
	 * Set mapping in presenter factory on DI initialization.
	 */
	public function onInitialize()
	{
		$this->presenterFactory->setMapping(array(
		    'SwapMeet' => 'Echo511\SwapMeet\Presenter\*Presenter',
		));
	}



	/* ----------- Subscriber ----------- */

	public function getSubscribedEvents()
	{
		return array(
		    'Nette\DI\Container::onInitialize'
		);
	}



}