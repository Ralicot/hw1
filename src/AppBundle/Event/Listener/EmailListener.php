<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 18.08.2015
 * Time: 17:54
 */

namespace AppBundle\Event\Listener;


use AppBundle\Event\Order\OrderAfterCreate;
use AppBundle\Communication\Email\Message;
use AppBundle\Service\EmailService;

class EmailListener
{
    /** @var  EmailService */
    private $emailService;
    public function sendConfirmationEmail(OrderAfterCreate $event)
    {
        $message = new Message;

        $message->setSubject('Thank you'. $event->getName() . "your order number" . $event->getOrder()->getId() . 'is being processed');
        $message->setAdditionalHeaders("From: no-reply@emag.ro");
        $this->emailService->send($message);
    }

    public function setEmailService(EmailService $emailService)
    {
        $this->emailService = $emailService;
        return $this;
    }
}