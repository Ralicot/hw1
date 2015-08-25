<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 25.08.2015
 * Time: 17:12
 */

namespace AppBundle\Event\Listener;

use AppBundle\Event\Document\DocumentEvent;
use AppBundle\Document\Document;
use AppBundle\Service\Communication\CommunicationService;
use Doctrine\ORM\EntityManager;


class InvoiceListener
{


    private $communicationService;
    private $manager;
    public function __construct(CommunicationService $communicationService, EntityManager $manager)
    {
        $this->communicationService = $communicationService;
        $this->manager = $manager;
    }

    public function sendInvoice(DocumentEvent $documentEvent)
    {
        $orderId = $documentEvent->getOrderNumber();
        var_dump('a');
        $order = $this->manager->getRepository('AppBundle:Order')->find($orderId);
        $customer = $order->getCustomer();
        $account = $this->manager->getRepository('AppBundle:Account')->find($customer);
        $email = $account->getEmail();
        $this->communicationService->sendInvoice( $email, $orderId);


    }
}