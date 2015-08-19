<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 19.08.2015
 * Time: 10:42
 */

namespace AppBundle\Service;

use AppBundle\Entity\Order;
use AppBundle\Event\Order\OrderEvent;

class DocumentService extends AbstractDoctrineAware
{
    const ID = 'app.document';
    public function generateInvoice(Order $order)
    {
        $this->eventDispatcher->dispatch(
            OrderEvent::INVOICE_GENERATED, new OrderEvent($order)
        );
    }
}