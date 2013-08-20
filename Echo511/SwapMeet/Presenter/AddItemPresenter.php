<?php

/**
 * This file is part of SwapMeet.
 *
 * Copyright (c) 2013 Nikolas Tsiongas (http://congi.cz)
 *
 * For the full copyright and license information, please view
 * the file license.txt that was distributed with this source code.
 */

namespace Echo511\SwapMeet\Presenter;

use Echo511\SwapMeet\Entity\Image;
use Echo511\SwapMeet\Entity\Item;
use Nette\Application\UI\Form;


/**
 * Add item to shop as seller.
 * 
 * @author Nikolas Tsiongas
 */
class AddItemPresenter extends BasePresenter
{

	/**
	 * Check permissions.
	 */
	public function startup()
	{
		parent::startup();
		if (!$this->user->isLoggedIn()) {
			$this->error('To add item you have to be logged in.', 403);
		}
	}



	/**
	 * Return for for adding single item.
	 * @return Form
	 */
	public function createComponentAddItemForm()
	{
		$form = new Form;

		$form->addUpload('image');
		$form->addText('title')
			->addRule(Form::FILLED);
		$form->addText('price')
			->addRule(Form::FILLED)
			->addRule(Form::NUMERIC);
		$form->addText('remaining')
			->addRule(Form::FILLED)
			->addRule(Form::NUMERIC);
		$form->addSubmit('submit');
		$form->onSuccess[] = callback($this, 'processAddItemForm');

		return $form;
	}



	/**
	 * Process form and add item into shop.
	 * @param Form $form
	 */
	public function processAddItemForm(Form $form)
	{
		$values = $form->values;

		$item = new Item;
		$item->assign($values, array('title', 'price', 'remaining'));
		$item->user = $this->user->getLoggedUser();

		$images = array();
		$images[] = new Image($values->image->getTemporaryFile(), null, $values->image->getName());

		$this->shop->addItem($item, $images);
	}



}