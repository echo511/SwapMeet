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

use Kdyby\Events\Subscriber;
use LeanMapper\Connection;
use Nette\Object;


/**
 * @author Nikolas Tsiongas
 */
class Transactions extends Object implements Subscriber
{

	/** @var Connection */
	private $connection;


	/**
	 * @param Connection $connection
	 */
	public function __construct(Connection $connection)
	{
		$this->connection = $connection;
	}



	/**
	 * Start transaction.
	 */
	public function onFirst()
	{
		$this->connection->query('START TRANSACTION;');
	}



	/**
	 * End transaction.
	 */
	public function onLast()
	{
		$this->connection->query('COMMIT;');
	}



	/**
	 * Rollback.
	 */
	public function onAbort()
	{
		$this->connection->query('ROLLBACK;');
	}



	/* ----------- Subscriber ----------- */

	public function getSubscribedEvents()
	{
		return array(
		    'Echo511\SwapMeet\Transactions\Transactions::onFirst',
		    'Echo511\SwapMeet\Transactions\Transactions::onLast',
		    'Echo511\SwapMeet\Transactions\Transactions::onAbort'
		);
	}



}