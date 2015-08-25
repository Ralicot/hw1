<?php

namespace AppBundle\Event\Listener;

use AppBundle\Entity\Order;
use AppBundle\Event\Order\OrderBeforeCreate;
use AppBundle\Event\Order\OrderEvent;
use AppBundle\Service\DeliveryService;
use AppBundle\Service\WarehouseService;
use AppBundle\Service\Document\DocumentService;
class OrderListener
{

    /**
     *
     * @var WarehouseService
     */
    private $warehouseService;

    /**
     *
     * @var DeliveryService
     */
    private $deliveryService;

    /**
     *
     * @var DocumentService
     */
    private $documentService;


    public function __construct(WarehouseService $warehouseService, DeliveryService $deliveryService, DocumentService $documentService)
    {
        $this->warehouseService = $warehouseService;
        $this->deliveryService = $deliveryService;
        $this->documentService = $documentService;
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
        $this->documentService->generateInvoice($event->getOrder());
    }

    public function onPackagingStart(OrderEvent $event)
    {
        $event->getOrder()->setStatus(Order::STATUS_PROCESSING_PACKAGING);
    }

    public function onPackagingEnd(OrderEvent $event)
    {
        $this->deliveryService->deliverProducts($event->getOrder());
    }

    public function onDeliveryStart(OrderEvent $event)
    {
        $event->getOrder()->setStatus(Order::STATUS_DELIVERY_STARTED);
    }

    public function onDeliveryEnd(OrderEvent $event)
    {
        $event->getOrder()->setStatus(Order::STATUS_DELIVERED);
    }

}
