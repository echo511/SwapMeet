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

use Kdyby\Events\EventManager;
use LeanMapper\Connection;
use LeanMapper\Entity;
use LeanMapper\IMapper;
use LeanMapper\Repository as LMRepository;


/**
 * Abstract repository.
 * 
 * @author Nikolas Tsiongas
 */
abstract class Repository extends LMRepository
{

	/** @var EventManager */
	private $eventManager;


	/**
	 * @param Connection $connection
	 * @param EventManager $eventManager
	 * @param IMapper $mapper
	 */
	public function __construct(Connection $connection, EventManager $eventManager, IMapper $mapper)
	{
		$this->eventManager = $eventManager;
		parent::__construct($connection, $mapper);
	}



	/**
	 * Return entity by primary key.
	 * @param int $primary
	 * @return Entity|bool
	 */
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



	/**
	 * Return entities - basic filtering, ordering, limit, offset.
	 * @param array $where
	 * @param array $order
	 * @param int $limit
	 * @param int $offset
	 * @return Entity[]
	 */
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
	  $event = $this->eventManager->createEvent($ns . '::' . $eventName);
	  $this->events->registerCallback($eventName, $event);
	  }
	  }
	 */

}