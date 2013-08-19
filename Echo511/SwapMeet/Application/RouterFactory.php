<?php

namespace Echo511\SwapMeet\Application;

use Kdyby\Events\Subscriber;
use Nette\Application\Routers\Route;
use Nette\Application\Routers\RouteList;
use Nette\Object;


class RouterFactory extends Object implements Subscriber
{

	/** @var RouteList */
	private $routeList;


	public function __construct(RouteList $routeList)
	{
		$this->routeList = $routeList;
	}



	public function onInitialize()
	{
		$this->routeList[] = new Route('<presenter>/<action>', array(
		    'module' => 'SwapMeet',
		    'presenter' => 'Browser',
		    'action' => 'default'
		));
	}



	public function getSubscribedEvents()
	{
		return array(
		    'Nette\DI\Container::onInitialize'
		);
	}



}