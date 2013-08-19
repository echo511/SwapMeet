<?php

/**
 * This file is part of SwapMeet.
 *
 * Copyright (c) 2013 Nikolas Tsiongas (http://congi.cz)
 *
 * For the full copyright and license information, please view
 * the file license.txt that was distributed with this source code.
 */

namespace Echo511\SwapMeet\Email;

use Echo511\SwapMeet\Entity\Customer;
use Echo511\SwapMeet\Entity\Order;
use Echo511\SwapMeet\Entity\User;
use Echo511\SwapMeet\Service\CancelOrder;
use Kdyby\Events\Subscriber;
use Nette\Application\Application;
use Nette\Application\UI\Presenter;
use Nette\Latte\Engine;
use Nette\Mail\IMailer;
use Nette\Mail\Message;
use Nette\Object;
use Nette\Templating\FileTemplate;


/**
 * Email sellers after successful order.
 * 
 * @author Nikolas Tsiongas
 */
class EmailSellerAfterOrder extends Object implements Subscriber
{

	/** @var CancelOrder */
	private $cancelOrder;

	/** @var IMailer */
	private $mailer;

	/** @var Presenter */
	private $presenter;


	/**
	 * @param Application $application
	 * @param CancelOrder $cancelOrder
	 * @param IMailer $mailer
	 */
	public function __construct(Application $application, CancelOrder $cancelOrder, IMailer $mailer)
	{
		$this->cancelOrder = $cancelOrder;
		$this->mailer = $mailer;
		$this->presenter = $application->getPresenter();
	}



	/**
	 * Send email on event.
	 * @param Order $order
	 * @param Customer $customer
	 */
	public function onSuccessOrder(Order $order, Customer $customer)
	{
		foreach ($this->getRecepients($order) as $recepient) {
			$mail = $this->createMessage($order, $recepient);
			$this->mailer->send($mail);
		}
	}



	/**
	 * Order can have items from multiple sellers. Return list of them.
	 * @param Order $order
	 * @return User[]
	 */
	protected function getRecepients(Order $order)
	{
		$recepients = array();
		foreach ($order->items as $item) {
			$recepients[md5($item->user->email)] = $item->user;
		}
		return $recepients;
	}



	/**
	 * Create mail message.
	 * @param Order $order
	 * @param User $recepient
	 * @return Message
	 */
	protected function createMessage(Order $order, User $recepient)
	{
		$mail = new Message;
		$mail->setFrom('noreply@congi.cz');
		$mail->setHtmlBody($this->createTemplate($order, $recepient));
		$mail->addTo($recepient->email);
		return $mail;
	}



	/**
	 * Create template.
	 * @param Order $order
	 * @param User $recepient
	 * @return FileTemplate
	 */
	protected function createTemplate(Order $order, User $recepient)
	{
		$template = new FileTemplate(__DIR__ . DIRECTORY_SEPARATOR . 'OrderEmails' . DIRECTORY_SEPARATOR . 'emailToSeller.latte');
		$template->registerFilter(new Engine);
		$template->registerHelperLoader('Nette\Templating\Helpers::loader');

		$template->buyersEmail = $order->buyersEmail;
		$template->cancelLink = $this->presenter->link('//:SwapMeet:CancelOrder:cancelItemsByUser', array(
		    'user_id' => $recepient->id,
		    'order_id' => $order->id,
		    'manipulation_link' => $this->cancelOrder->createManipulationPass($recepient, $order),
		));
		$template->items = array();
		foreach ($order->items as $item) {
			if ($item->user->id == $recepient->id) {
				$template->items[] = $item;
			}
		}

		return $template;
	}



	/* ----------- Subscriber ----------- */

	public function getSubscribedEvents()
	{
		return array(
		    'Echo511\SwapMeet\Entity\Shop::onSuccessOrder'
		);
	}



}