<?php

namespace Echo511\SwapMeet\Repository;

use Echo511\SwapMeet\Entity\Image;
use Echo511\SwapMeet\Entity\Item;
use Nette\Object;
use Nette\Utils\Finder;


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