<?php

/**
 * This file is part of SwapMeet.
 *
 * Copyright (c) 2013 Nikolas Tsiongas (http://congi.cz)
 *
 * For the full copyright and license information, please view
 * the file license.txt that was distributed with this source code.
 */

namespace Echo511\SwapMeet\Entity;


/**
 * @author Nikolas Tsiongas
 */
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