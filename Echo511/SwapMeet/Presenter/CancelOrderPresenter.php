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

use Echo511\SwapMeet\Repository\OrderRepository;
use Echo511\SwapMeet\Repository\UserRepository;
use Echo511\SwapMeet\Service\CancelOrder;


/**
 * Cancel order as seller.
 * 
 * @author Nikolas Tsiongas
 */
class CancelOrderPresenter extends BasePresenter
{

	/** @var CancelOrder */
	private $cancelOrder;

	/** @var OrderRepository */
	private $orderRepository;

	/** @var UserRepository */
	private $userRepository;


	/**
	 * Inject dependencies.
	 * @param CancelOrder $cancelOrder
	 * @param OrderRepository $orderRepository
	 * @param UserRepository $userRepository
	 */
	public function injectCancelOrderPresenter(CancelOrder $cancelOrder, OrderRepository $orderRepository, UserRepository $userRepository)
	{
		$this->cancelOrder = $cancelOrder;
		$this->orderRepository = $orderRepository;
		$this->userRepository = $userRepository;
	}



	/**
	 * Remove items from order owned by seller.
	 * @param int $user_id
	 * @param int $order_id
	 * @param string $manipulation_link
	 */
	public function actionCancelItemsByUser($user_id, $order_id, $manipulation_link)
	{
		$user = $this->userRepository->get($user_id);
		$order = $this->orderRepository->get($order_id);

		if ($user && $order) {
			if ($this->cancelOrder->createManipulationPass($user, $order) == $manipulation_link) {
				$this->cancelOrder->cancelItemsByUser($user, $order);
				// @TODO english
				$this->flashMessage('Objednávka smazána.', 'success');
			} else {
				// @TODO english
				$this->flashMessage('Nelze smazat. Chybný odkaz.', 'error');
			}
		} else {
			// @TODO english
			$this->flashMessage('Nelze smazat. Chybný odkaz.', 'error');
		}

		$this->setView('default');
	}



}