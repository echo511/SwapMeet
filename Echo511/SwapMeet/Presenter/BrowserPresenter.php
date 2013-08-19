<?php

namespace Echo511\SwapMeet\Presenter;

use Echo511\SwapMeet\Repository\ImageRepository;
use Echo511\SwapMeet\Repository\ItemRepository;
use Echo511\SwapMeet\Service\AvailabilityService;


class BrowserPresenter extends BasePresenter
{

	/** @var AvailabilityService */
	private $availabilityService;

	/** @var ImageRepository */
	private $imageRepository;

	/** @var ItemRepository */
	private $itemRepository;


	public function injectBrowserPresenter(AvailabilityService $availabilityService, ImageRepository $imageRepository, ItemRepository $itemRepository)
	{
		$this->availabilityService = $availabilityService;
		$this->imageRepository = $imageRepository;
		$this->itemRepository = $itemRepository;
	}



	public function handleAddToCart($item_id)
	{
		$item = $this->itemRepository->get($item_id);
		$this->customer->requestItem($item, $this->shop);

		if ($this->isAjax()) {
			$this->invalidateControl('items');
			$this->invalidateControl('cart');
		} else {
			$this->redirect('this');
		}
	}



	public function renderDefault()
	{
		$this->template->items = $this->itemRepository->findAll();
		$this->template->imageRepository = $this->imageRepository;
		$this->template->availabilityMap = $this->availabilityService->createAvailabilityMap($this->template->items);
	}



}