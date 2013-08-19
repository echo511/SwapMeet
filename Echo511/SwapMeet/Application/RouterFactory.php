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
use Nette\Application\Routers\Route;
use Nette\Application\Routers\RouteList;
use Nette\Object;


/**
 * Routes for application.
 * 
 * @author Nikolas Tsiongas
 */
class RouterFactory extends Object implements Subscriber
{

	/** @var RouteList */
	private $routeList;


	/**
	 * @param RouteList $routeList
	 */
	public function __construct(RouteList $routeList)
	{
		$this->routeList = $routeList;
	}



	/**
	 * Create routes on DI initialization.
	 */
	public function onInitialize()
	{
		$this->routeList[] = new Route('<presenter>/<action>', array(
		    'module' => 'SwapMeet',
		    'presenter' => 'Browser',
		    'action' => 'default'
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