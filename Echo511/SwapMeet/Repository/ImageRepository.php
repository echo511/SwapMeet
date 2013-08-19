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
 * @author Nikolas Tsiongas
 */
class ImageRepository extends Object
{

	private $basePath;
	private $dir;


	public function __construct($dir, $basePath)
	{
		$this->basePath = $basePath;
		$this->dir = $dir;
	}



	public function getAllByItem(Item $item)
	{
		$dir = $this->dir . DIRECTORY_SEPARATOR . $item->id;

		if (is_dir($dir)) {
			$finder = Finder::findFiles('*')
				->from($dir);

			$basePath = $this->basePath . '/' . $item->id;
			return $this->createEntities($finder, $basePath);
		}
		return array();
	}



	private function createEntities(Finder $finder, $basePath)
	{
		$entities = array();
		foreach ($finder as $filename => $spl) {
			$baseFilename = $basePath . '/' . basename($filename);
			$entities[] = new Image($filename, $baseFilename);
		}
		return $entities;
	}



}