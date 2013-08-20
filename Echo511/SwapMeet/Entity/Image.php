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
 * Image.
 * 
 * @property-read string $name
 * @property-read string $filename
 * @property-read string $baseFilename
 * 
 * @author Nikolas Tsiongas
 */
class Image extends \Nette\Object
{

	private $name;
	private $filename;
	private $baseFilename;


	/**
	 * @param string $filename
	 * @param string|null $baseFilename
	 * @param string|null $name
	 */
	public function __construct($filename, $baseFilename = null, $name = null)
	{
		$this->filename = $filename;
		$this->baseFilename = $baseFilename;
		$this->name = $name;
	}



	/**
	 * Return image basename.
	 * Cannot be extracted from filename when filename points to temp file.
	 * @return string
	 */
	public function getName()
	{
		return $this->name;
	}



	/**
	 * Return system filename.
	 * @return string
	 */
	public function getFilename()
	{
		return $this->filename;
	}



	/**
	 * Return URL for browser.
	 * @return string
	 */
	public function getBaseFilename()
	{
		return $this->baseFilename;
	}



}