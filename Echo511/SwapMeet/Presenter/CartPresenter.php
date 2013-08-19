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

use Echo511\SwapMeet\Entity\Order;
use Echo511\SwapMeet\Repository\ImageRepository;
use Echo511\SwapMeet\Repository\ItemRepository;
use Nette\Application\UI\Form;


/**
 * Customer's cart.
 * 
 * @author Nikolas Tsiongas
 */
class CartPresenter extends BasePresenter
{

	/** @var ImageRepository */
	private $imageRepository;

	/** @var ItemRepository */
	private $itemRepository;


	/**
	 * Inject dependencies.
	 * @param ImageRepository $imageRepository
	 * @param ItemRepository $itemRepository
	 */
	public function injectCartPresenter(ImageRepository $imageRepository, ItemRepository $itemRepository)
	{
		$this->imageRepository = $imageRepository;
		$this->itemRepository = $itemRepository;
	}



	/**
	 * Remove item from cart.
	 * @param int $item_id
	 */
	public function handleRemoveFromCart($item_id)
	{
		$item = $this->itemRepository->get($item_id);
		$this->customer->returnItem($item, $this->shop);

		if ($this->isAjax()) {
			$this->invalidateControl('content');
			$this->invalidateControl('cart');
		} else {
			$this->redirect('this');
		}
	}



	/**
	 * Render.
	 */
	public function renderDefault()
	{
		$this->template->cart = $this->customer->getCart();
		$this->template->items = $this->customer->getCart()->items;
		$this->template->imageRepository = $this->imageRepository;
	}



	/**
	 * Create order form.
	 * @return Form
	 */
	public function createComponentOrderForm()
	{
		$form = new Form;
		$form->addText('email')
			->addRule(Form::EMAIL);
		$form->addSubmit('submit');
		$form->onSuccess[] = callback($this, 'processOrder');
		return $form;
	}



	/**
	 * Process order form.
	 * @param Form $form
	 */
	public function processOrder(Form $form)
	{
		if (count($this->customer->getCart()->items) > 0) {
			$order = new Order;
			$order->buyersEmail = $form->values->email;
			$this->customer->submitOrder($order, $this->shop);
		}
	}



}