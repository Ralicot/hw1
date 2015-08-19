<?php

namespace AppBundle\Event\Listener;

use AppBundle\Event\Order\OrderBeforeCreate;
use AppBundle\Event\Order\OrderEvent;
use AppBundle\Entity\Order;
use AppBundle\Service\DeliveryService;
use AppBundle\Service\WarehouseService;
use AppBundle\Event\StatusChange;

class OrderListener
{
    const ID = 'app.order_listener';
    /**
     *
     * @var WarehouseService
     */
    private $warehouseService;

    /**
     *
     * @var DeliveryService
     */
    private $deliverySerice;




    public function __construct(
        WarehouseService $warehouseService,
        DeliveryService $deliveryService
    )
    {
        $this->deliverySerice = $deliveryService;
        $this->warehouseService = $warehouseService;
    }

    public function onBeforeCreate(OrderBeforeCreate $event)
    {

    }
    public function onAfterCreate(OrderEvent $event)
    {
        $this->warehouseService->reserveProducts($event->getOrder());
    }
    public function onReservationFailed(OrderEvent $event)
    {
        $event->getOrder()->setStatus(Order::STATUS_PROCESSING_PRODUCTS_MISSING);
    }
    public function onProductsReserved(OrderEvent $event)
    {
        $event->getOrder()->setStatus(Order::STATUS_PROCESSING_PRODUCTS_RESERVED);
        $this->warehouseService->packageProducts($event->getOrder());
    }
    public function onPackagingStart(OrderEvent $event)
    {
        $event->getOrder()->setStatus(Order::STATUS_PROCESSING_PACKAGING);
    }
    public function onPackagingEnd(OrderEvent $event)
    {
        $this->deliverySerice->deliverProducts($event->getOrder());
    }
    public function onDeliveryStart(OrderEvent $event)
    {
        $event->getOrder()->setStatus(Order::STATUS_DELIVERY_STARTED);
    }
    public function onDeliveryEnd(OrderEvent $event)
    {
        $event->getOrder()->setStatus(Order::STATUS_DELIVERED);
    }




//    public function setWarehouseService(WarehouseService $warehouseService)
//    {
//        $this->warehouseService = $warehouseService;
//        return $this;
//    }




}