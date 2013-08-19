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
use Kdyby\Events\Subscriber;
use Nette\Latte\Engine;
use Nette\Mail\IMailer;
use Nette\Mail\Message;
use Nette\Object;
use Nette\Templating\FileTemplate;


/**
 * Email buyer after successful order.
 * 
 * @author Nikolas Tsiongas
 */
class EmailBuyerAfterOrder extends Object implements Subscriber
{

	/** @var IMailer */
	private $mailer;


	/**
	 * @param IMailer $mailer
	 */
	public function __construct(IMailer $mailer)
	{
		$this->mailer = $mailer;
	}



	/**
	 * Send email on event.
	 * @param Order $order
	 * @param Customer $customer
	 */
	public function onSuccessOrder(Order $order, Customer $customer)
	{
		$mail = $this->createMessage($order);
		$this->mailer->send($mail);
	}



	/**
	 * Create mail message.
	 * @param Order $order
	 * @return Message
	 */
	protected function createMessage(Order $order)
	{
		$mail = new Message;
		$mail->setFrom('noreply@congi.cz');
		$mail->setHtmlBody($this->createTemplate($order));
		$mail->addTo($order->buyersEmail);
		return $mail;
	}



	/**
	 * Create template.
	 * @param Order $order
	 * @return FileTemplate
	 */
	protected function createTemplate(Order $order)
	{
		$template = new FileTemplate(__DIR__ . DIRECTORY_SEPARATOR . 'OrderEmails' . DIRECTORY_SEPARATOR . 'emailToBuyer.latte');
		$template->registerFilter(new Engine);
		$template->registerHelperLoader('Nette\Templating\Helpers::loader');

		$template->items = $order->items;

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