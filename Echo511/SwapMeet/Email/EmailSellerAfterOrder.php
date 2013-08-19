<?php

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


class EmailSellerAfterOrder extends Object implements Subscriber
{

	/** @var IMailer */
	private $mailer;

	/** @var Presenter */
	private $presenter;

	/** @var CancelOrder */
	private $cancelOrder;


	public function __construct(IMailer $mailer, Application $application, CancelOrder $cancelOrder)
	{
		$this->mailer = $mailer;
		$this->presenter = $application->getPresenter();
		$this->cancelOrder = $cancelOrder;
	}



	public function getSubscribedEvents()
	{
		return array(
		    'Echo511\SwapMeet\Entity\Shop::onSuccessOrder'
		);
	}



	public function onSuccessOrder(Order $order, Customer $customer)
	{
		foreach ($this->getRecepients($order) as $recepient) {
			$mail = $this->createMessage($order, $recepient);
			$this->mailer->send($mail);
		}
	}



	protected function getRecepients(Order $order)
	{
		$recepients = array();
		foreach ($order->items as $item) {
			$recepients[md5($item->user->email)] = $item->user;
		}
		return $recepients;
	}



	protected function createMessage(Order $order, User $recepient)
	{
		$mail = new Message;
		$mail->setFrom('noreply@congi.cz');
		$mail->setHtmlBody($this->createTemplate($order, $recepient));
		$mail->addTo($recepient->email);
		return $mail;
	}



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



}