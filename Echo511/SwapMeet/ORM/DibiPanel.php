<?php

namespace Echo511\SwapMeet\ORM;

use DibiEvent;
use DibiNettePanel;
use Kdyby\Events\Subscriber;


class DibiPanel extends DibiNettePanel implements Subscriber
{

	public function onEvent(DibiEvent $dibiEvent)
	{
		$this->logEvent($dibiEvent);
	}



	public function getSubscribedEvents()
	{
		return array(
		    'DibiConnection::onEvent'
		);
	}



}