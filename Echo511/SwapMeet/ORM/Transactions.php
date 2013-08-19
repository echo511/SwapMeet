<?php

namespace Echo511\SwapMeet\ORM;

use Kdyby\Events\Subscriber;
use LeanMapper\Connection;
use Nette\Object;


class Transactions extends Object implements Subscriber
{

	/** @var Connection */
	private $connection;


	public function __construct(Connection $connection)
	{
		$this->connection = $connection;
	}



	public function onFirst()
	{
		$this->connection->query('START TRANSACTION;');
	}



	public function onLast()
	{
		$this->connection->query('COMMIT;');
	}



	public function onAbort()
	{
		$this->connection->query('ROLLBACK;');
	}



	public function getSubscribedEvents()
	{
		return array(
		    'Echo511\SwapMeet\Transactions\Transactions::onFirst',
		    'Echo511\SwapMeet\Transactions\Transactions::onLast',
		    'Echo511\SwapMeet\Transactions\Transactions::onAbort'
		);
	}



}