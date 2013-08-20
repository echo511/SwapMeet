<?php

/**
 * This file is part of SwapMeet.
 *
 * Copyright (c) 2013 Nikolas Tsiongas (http://congi.cz)
 *
 * For the full copyright and license information, please view
 * the file license.txt that was distributed with this source code.
 */

namespace Echo511\SwapMeet\Repository;

use Echo511\SwapMeet\Entity\Image;
use Echo511\SwapMeet\Entity\Item;
use Nette\Object;
use Nette\Utils\Finder;


/**
 * Image repository.
 * 
 * @author Nikolas Tsiongas
 */
class ImageRepository extends Object
{

	/** @var string */
	private $basePath;

	/** @var string */
	private $dir;


	/**
	 * @param string $dir
	 * @param string $basePath
	 */
	public function __construct($dir, $basePath)
	{
		$this->basePath = $basePath;
		$this->dir = $dir;
	}



	/**
	 * Associate image with item.
	 * @param Image $image
	 * @param Item $item
	 */
	public function persist(Image $image, Item $item)
	{
		copy($image->filename, $this->dirForItem($item) . DIRECTORY_SEPARATOR . $image->getName());
	}



	/**
	 * Return all images for item.
	 * @param Item $item
	 * @return Image[]
	 */
	public function getAllByItem(Item $item)
	{
		$dir = $this->dirForItem($item);
		if (is_dir($dir)) {
			$finder = Finder::findFiles('*')
				->from($dir);

			$basePath = $this->basePath . '/' . $item->id;
			return $this->createEntities($finder, $basePath);
		}
		return array();
	}



	/**
	 * Return images in specified directory.
	 * @param Finder $finder
	 * @param string $basePath
	 * @return Image[]
	 */
	protected function createEntities(Finder $finder, $basePath)
	{
		$entities = array();
		foreach ($finder as $filename => $spl) {
			$baseFilename = $basePath . '/' . basename($filename);
			$entities[] = new Image($filename, $baseFilename);
		}
		return $entities;
	}



	/**
	 * Return path for item's images directory.
	 * @param Item $item
	 * @return string
	 */
	protected function dirForItem(Item $item)
	{
		return $this->dir . DIRECTORY_SEPARATOR . $item->id;
	}



}