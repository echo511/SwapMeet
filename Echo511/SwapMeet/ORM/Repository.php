<?php

namespace Echo511\SwapMeet\ORM;

use DibiRow;
use Kdyby\Events\EventManager;
use LeanMapper\Connection;
use LeanMapper\Events;
use LeanMapper\IMapper;
use LeanMapper\Repository as LMRepository;
use LeanMapper\Result;
use Nette\DI\Container;


abstract class Repository extends LMRepository
{

	/** @var EventManager */
	private $evm;


	public function __construct(Connection $connection, IMapper $mapper, EventManager $evm)
	{
		$this->evm = $evm;
		parent::__construct($connection, $mapper);
	}



	public function get($primary)
	{
		$row = $this->connection->select('*')
			->from($this->getTable())
			->where('[id] = ?', $primary)
			->fetch();

		if (!$row) {
			return false;
		}

		return $this->createEntity($row);
	}



	public function findAll(array $where = null, array $order = null, $limit = null, $offset = null)
	{
		$query = $this->connection->select('*')
			->from($this->getTable());

		if ($where !== null) {
			foreach ($where as $cond) {
				$query->where($cond);
			}
		}

		if ($order !== null) {
			list($col, $sort) = $order;
			$query->orderBy($col);
			$query->{$sort . "()"};
		}

		$rows = $query->fetchAll($offset, $limit);
		return $this->createEntities($rows);
	}



	/*
	  protected function initEvents()
	  {
	  static $events = array(
	  Events::EVENT_BEFORE_PERSIST,
	  Events::EVENT_BEFORE_CREATE,
	  Events::EVENT_BEFORE_UPDATE,
	  Events::EVENT_BEFORE_DELETE,
	  Events::EVENT_AFTER_PERSIST,
	  Events::EVENT_AFTER_CREATE,
	  Events::EVENT_AFTER_UPDATE,
	  Events::EVENT_AFTER_DELETE,
	  );

	  foreach ($events as $eventName) {
	  $ns = get_class($this);
	  $event = $this->evm->createEvent($ns . '::' . $eventName);
	  $this->events->registerCallback($eventName, $event);
	  }
	  }
	 */

}