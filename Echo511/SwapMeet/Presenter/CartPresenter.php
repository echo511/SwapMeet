<?php

namespace Echo511\SwapMeet\Presenter;

use DateTime;
use Echo511\SwapMeet\Entity\Order;
use Echo511\SwapMeet\Repository\ImageRepository;
use Echo511\SwapMeet\Repository\ItemRepository;
use Nette\Application\UI\Form;


class CartPresenter extends BasePresenter
{

	/** @var ImageRepository */
	private $imageRepository;

	/** @var ItemRepository */
	private $itemRepository;


	public function injectCartPresenter(ImageRepository $imageRepository, ItemRepository $itemRepository)
	{
		$this->imageRepository = $imageRepository;
		$this->itemRepository = $itemRepository;
	}



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



	public function renderDefault()
	{
		$this->template->cart = $this->customer->getCart();
		$this->template->items = $this->customer->getCart()->items;
		$this->template->imageRepository = $this->imageRepository;
	}



	public function createComponentOrderForm()
	{
		$form = new Form;
		$form->addText('email')
			->addRule(Form::EMAIL);
		$form->addSubmit('submit');
		$form->onSuccess[] = callback($this, 'processOrder');
		return $form;
	}



	public function processOrder(Form $form)
	{
		if (count($this->customer->getCart()->items) > 0) {
			$order = new Order;
			$order->buyersEmail = $form->values->email;
			$this->customer->submitOrder($order, $this->shop);
		}
	}



}