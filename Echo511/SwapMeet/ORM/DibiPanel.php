<?php

/**
 * This file is part of SwapMeet.
 *
 * Copyright (c) 2013 Nikolas Tsiongas (http://congi.cz)
 *
 * For the full copyright and license information, please view
 * the file license.txt that was distributed with this source code.
 */

namespace Echo511\SwapMeet\ORM;

use DibiEvent;
use DibiNettePanel;
use Kdyby\Events\Subscriber;


/**
 * Dibi panel
 * 
 * @author Nikolas Tsiongas
 */
class DibiPanel extends DibiNettePanel implements Subscriber
{

	/**
	 * Log query.
	 * @param DibiEvent $dibiEvent
	 */
	public function onEvent(DibiEvent $dibiEvent)
	{
		$this->logEvent($dibiEvent);
	}



	/* ----------- Subscriber ----------- */

	public function getSubscribedEvents()
	{
		return array(
		    'DibiConnection::onEvent'
		);
	}



}