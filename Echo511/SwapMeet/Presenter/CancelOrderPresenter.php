<?php

namespace Echo511\SwapMeet\Presenter;

use Echo511\SwapMeet\Repository\OrderRepository;
use Echo511\SwapMeet\Repository\UserRepository;
use Echo511\SwapMeet\Service\CancelOrder;


class CancelOrderPresenter extends BasePresenter
{

	/** @var CancelOrder */
	private $cancelOrder;

	/** @var OrderRepository */
	private $orderRepository;

	/** @var UserRepository */
	private $userRepository;


	public function injectCancelOrderPresenter(CancelOrder $cancelOrder, OrderRepository $orderRepository, UserRepository $userRepository)
	{
		$this->cancelOrder = $cancelOrder;
		$this->orderRepository = $orderRepository;
		$this->userRepository = $userRepository;
	}



	public function actionCancelItemsByUser($user_id, $order_id, $manipulation_link)
	{
		$user = $this->userRepository->get($user_id);
		$order = $this->orderRepository->get($order_id);

		if ($user && $order) {
			if ($this->cancelOrder->createManipulationPass($user, $order) == $manipulation_link) {
				$this->cancelOrder->cancelItemsByUser($user, $order);
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