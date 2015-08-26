<?php

namespace AppBundle\Service\Communication;

use AppBundle\Document\Document;

use AppBundle\Communication\Email\Message;
use AppBundle\Document\Email;
use AppBundle\Entity\Order;
use AppBundle\Event\Communication\Email\EmailEvent;
use AppBundle\Event\Communication\Email\EmailSendingEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Templating\EngineInterface as Templating;
use Symfony\Component\Translation\TranslatorInterface as Translator;
use Doctrine\Bundle\MongoDBBundle\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Registry;
use OldSound\RabbitMqBundle\RabbitMq\Producer;
use PhpAmqpLib\Message\AMQPMessage;

class CommunicationService
{

    const ID = 'app.communication';

    /**
     *
     * @var EmailService
     */
    private $emailService;

    /**
     *
     * @var Templating
     */
    private $twigEngine;

    /**
     *
     * @var Translator 
     */
    private $translator;

    /**
     *
     * @var EventDispatcherInterface 
     */
    private $eventDispatcher;

    /**
     * @var Registry
     */
    private $doctrine;

    /**
     * @var ManagerRegistry
     */

    private $documentManager;

    /**
     * @var Producer
     */
    private $emailProducer;

    public function __construct(
            EmailService $emailService,
            Templating $twigEngine,
            Translator $translator,
            EventDispatcherInterface $eventDispatcher,
            ManagerRegistry $documentManager,
            Registry $doctrine,
            Producer $emailProducer

    )
    {
        $this->emailService = $emailService;
        $this->twigEngine = $twigEngine;
        $this->translator = $translator;
        $this->eventDispatcher = $eventDispatcher;
        $this->documentManager = $documentManager;
        $this->doctrine = $doctrine;
        $this->emailProducer = $emailProducer;
    }

    public function sendConfirmationEmail($emailAddress, $name, $orderNumber, $locale = 'en')
    {
        $arguments = array('customerName' => $name, 'orderNumber' => $orderNumber);
        return $this->sendEmail('confirmation', $emailAddress, $arguments, $locale);
    }

    public function sendDeliveryEmail($emailAddress, $orderNumber)
    {
        
    }

    public function sendInvoice($emailAddress, $orderNumber)
    {

        $order  = $this->doctrine->getRepository(Order::REPOSITORY)->find($orderNumber);
        $customer = $order->getCustomer();
        $name = $customer->getContact()->getName();

        $arguments = array('customerName'=> $name, 'orderNumber' => $orderNumber );

        return $this->sendEmail('invoice',$emailAddress,$arguments);

    }

    public function sendSatisfactionSurvey($emailAddress, $orderNumber)
    {
        
    }

    public function sendEmail($type, $emailAddress, $arguments, $locale = 'en')
    {
        $this->translator->setLocale($locale);
        $message = $this->constructEmailMessage($type, $emailAddress, $arguments);
        $this->dispatchEmailSendingEvent($type,$arguments,$message,Email::STATUS_STASHED);
        $email = $this->documentManager->getRepository(Email::REPOSITORY)->findOneBy(array('type'=>$type, 'arguments' => $arguments));
        var_dump($email->getId());
        $this->emailProducer->publish($email->getId(), 'email.message');

        return Email::STATUS_STASHED;
    }
    
    private function dispatchEmailSendingEvent($type, $arguments, $message, $status)
    {
        $event = new EmailSendingEvent($type, $arguments, $message);

        $eventNames = array(
            Email::STATUS_STASHED => EmailSendingEvent::BEFORE_SEND,
            Email::STATUS_SENT => EmailSendingEvent::SENT,
            Email::STATUS_TEMPORARY_ERROR => EmailSendingEvent::ERROR_TEMPORARY,
            Email::STATUS_PERMANENT_ERROR => EmailSendingEvent::ERROR_PERMANENT
        );
        $this->eventDispatcher->dispatch($eventNames[$status], $event);
    }

    private function renderTempalate($type, $arguments)
    {
        if($type === 'invoice')
        {
            $document = $this->documentManager->getRepository(Document::REPOSITORY)->findOneBy(array('orderNumber' => $arguments['orderNumber']));
            return $document->getBodyHtml();
        }
        else
        {
            $templateName = sprintf('AppBundle:Templates:Email/%s.html.twig', $type);
            return $this->twigEngine->render($templateName, $arguments);
        }
    }

    private function getSubject($type, $arguments)
    {
        $translationKey = sprintf('%s.subject', $type);
        return $this->translator->trans($translationKey, $arguments, 'email');
    }

    private function getFrom($type)
    {
        $translationKey = sprintf('%s.from', $type);
        return $this->translator->trans($translationKey, array(), 'email');
    }

    private function constructEmailMessage($type, $emailAddress, $arguments)
    {
        $message = new Message();
        $message->setTo($emailAddress);
        $message->setMessage($this->renderTempalate($type, $arguments));
        $message->setSubject($this->getSubject($type, $arguments));
        $message->setFrom($this->getFrom($type));
        return $message;
    }


    public function execute(AMQPMessage $amqpMessage)
    {
        $emailId = $amqpMessage->body;
        $repository = $this->documentManager->getRepository(Email::REPOSITORY);
        $email = $repository->find($emailId);
        $message = $this->constructEmailMessage($email->getType(),$email->getEmailAddress(),$email->getArguments());
        $status = $this->emailService->send($message);
        $this->dispatchEmailSendingEvent($email->getType(), $email->getArguments(), $message, $status);



        return true;
    }

}
