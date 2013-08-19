<?php

/**
 * This file is part of SwapMeet.
 *
 * Copyright (c) 2013 Nikolas Tsiongas (http://congi.cz)
 *
 * For the full copyright and license information, please view
 * the file license.txt that was distributed with this source code.
 */

namespace Echo511\SwapMeet\Service;

use Echo511\SwapMeet\Entity\Item;
use Echo511\SwapMeet\Repository\CartRepository;
use LeanMapper\Connection;
use Nette\Object;


/**
 * Check availability of items in shop.
 * 
 * @author Nikolas Tsiongas
 */
class AvailabilityService extends Object
{

	/** @var CartRepository */
	private $cartRepository;

	/** @var Connection */
	private $connection;


	/**
	 * @param CartRepository $cartRepository
	 * @param Connection $connection
	 */
	public function __construct(CartRepository $cartRepository, Connection $connection)
	{
		$this->cartRepository = $cartRepository;
		$this->connection = $connection;
	}



	/**
	 * Return how many items are left.
	 * @param \Echo511\SwapMeet\Entity\Item $item
	 * @return type
	 */
	public function countAvailability(Item $item)
	{
		$map = $this->createAvailabilityMap(array($item));
		return $map[$item->id];
	}



	/**
	 * Return map $item->id => $available.
	 * @param Item[] $items
	 * @return array
	 */
	public function createAvailabilityMap($items)
	{
		$result = $this->connection->select('[item_id], COUNT([item_id]) AS [total]')
			->from('cart_item')
			->where('[item_id] IN(?)', $this->collectionToIds($items))
			->where('[cart_id] IN(?)', $this->collectionToIds($this->cartRepository->getRecent()))
			->groupBy('item_id')
			->fetchAll();

		$availability = array();
		foreach ($result as $row) {
			$availability[$row->item_id] = $row->total;
		}

		$map = array();
		foreach ($items as $item) {
			if (isset($availability[$item->id])) {
				$map[$item->id] = $item->remaining - $availability[$item->id];
			} else {
				$map[$item->id] = $item->remaining;
			}
		}
		return $map;
	}



	private function collectionToIds($collection)
	{
		$ids = array();
		foreach ($collection as $item) {
			$ids[$item->id] = $item->id;
		}
		if (empty($ids)) {
			return null;
		}
		return $ids;
	}



}