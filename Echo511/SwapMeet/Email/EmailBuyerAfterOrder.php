<?php

namespace Echo511\SwapMeet\Email;

use Echo511\SwapMeet\Entity\Customer;
use Echo511\SwapMeet\Entity\Order;
use Kdyby\Events\Subscriber;
use Nette\Latte\Engine;
use Nette\Mail\IMailer;
use Nette\Mail\Message;
use Nette\Object;
use Nette\Templating\FileTemplate;


class EmailBuyerAfterOrder extends Object implements Subscriber
{

	/** @var IMailer */
	private $mailer;


	public function __construct(IMailer $mailer)
	{
		$this->mailer = $mailer;
	}



	public function getSubscribedEvents()
	{
		return array(
		    'Echo511\SwapMeet\Entity\Shop::onSuccessOrder'
		);
	}



	public function onSuccessOrder(Order $order, Customer $customer)
	{
		$mail = $this->createMessage($order);
		$this->mailer->send($mail);
	}



	protected function createMessage(Order $order)
	{
		$mail = new Message;
		$mail->setFrom('noreply@congi.cz');
		$mail->setHtmlBody($this->createTemplate($order));
		$mail->addTo($order->buyersEmail);
		return $mail;
	}



	protected function createTemplate(Order $order)
	{
		$template = new FileTemplate(__DIR__ . DIRECTORY_SEPARATOR . 'OrderEmails' . DIRECTORY_SEPARATOR . 'emailToBuyer.latte');
		$template->registerFilter(new Engine);
		$template->registerHelperLoader('Nette\Templating\Helpers::loader');

		$template->items = $order->items;

		return $template;
	}



}