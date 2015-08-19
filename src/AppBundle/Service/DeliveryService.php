<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 19.08.2015
 * Time: 10:36
 */

namespace AppBundle\Service;

use AppBundle\Entity\Order;
use AppBundle\Event\Order\OrderEvent;

class DeliveryService extends AbstractDoctrineAware
{
    const ID = 'app.delivery';

    public function deliverProducts(Order $order)
    {
        $this->eventDispatcher->dispatch(
            OrderEvent::DELIVERY_START, new OrderEvent($order)
        );
        $this->eventDispatcher->dispatch(
            OrderEvent::DELIVERY_END, new OrderEvent($order)
        );

    }

}