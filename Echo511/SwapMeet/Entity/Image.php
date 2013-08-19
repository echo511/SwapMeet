<?php

namespace Echo511\SwapMeet\Entity;


class Image extends \Nette\Object
{

	private $filename;
	private $baseFilename;


	public function __construct($filename, $baseFilename)
	{
		$this->filename = $filename;
		$this->baseFilename = $baseFilename;
	}



	public function getFilename()
	{
		return $this->filename;
	}



	public function getBaseFilename()
	{
		return $this->baseFilename;
	}



}