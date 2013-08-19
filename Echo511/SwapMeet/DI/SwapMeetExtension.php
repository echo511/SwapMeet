<?php

/**
 * This file is part of SwapMeet.
 *
 * Copyright (c) 2013 Nikolas Tsiongas (http://congi.cz)
 *
 * For the full copyright and license information, please view
 * the file license.txt that was distributed with this source code.
 */

namespace Echo511\SwapMeet\DI;

use Nette\DI\CompilerExtension;


/**
 * DI compiler extension.
 * 
 * @author Nikolas Tsiongas
 */
class SwapMeetExtension extends CompilerExtension
{

	public function loadConfiguration()
	{
		$builder = $this->getContainerBuilder();
		$config = $this->loadFromFile(__DIR__ . '/../config/swapmeet.neon');
		$this->compiler->parseServices($builder, $config, 'swapMeet');
	}



}