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

use Echo511\SwapMeet\Repository\ImageRepository;
use Echo511\SwapMeet\Repository\ItemRepository;
use Echo511\SwapMeet\Service\AvailabilityService;


/**
 * Display offers to customer.
 * 
 * @author Nikolas Tsiongas
 */
class BrowserPresenter extends BasePresenter
{

	/** @var AvailabilityService */
	private $availabilityService;

	/** @var ImageRepository */
	private $imageRepository;

	/** @var ItemRepository */
	private $itemRepository;


	/**
	 * Inject dependencies.
	 * @param AvailabilityService $availabilityService
	 * @param ImageRepository $imageRepository
	 * @param ItemRepository $itemRepository
	 */
	public function injectBrowserPresenter(AvailabilityService $availabilityService, ImageRepository $imageRepository, ItemRepository $itemRepository)
	{
		$this->availabilityService = $availabilityService;
		$this->imageRepository = $imageRepository;
		$this->itemRepository = $itemRepository;
	}



	/**
	 * Add item to cart.
	 * @param int $item_id
	 */
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



	/**
	 * Render.
	 */
	public function renderDefault()
	{
		$this->template->items = $this->itemRepository->findAll();
		$this->template->imageRepository = $this->imageRepository;
		$this->template->availabilityMap = $this->availabilityService->createAvailabilityMap($this->template->items);
	}



}