<?php

namespace AppBundle\Service;

use AppBundle\Entity\Customer;
use AppBundle\Entity\Order;
use AppBundle\Entity\OrderProductLine;
use AppBundle\Entity\ProductSale;
use AppBundle\Event\Order\OrderBeforeCreate;
use AppBundle\Event\Order\OrderEvent;

class OrderService extends AbstractDoctrineAware
{

    const ID = 'app.order';

    public function createOrder($customerId, $products)
    {
        $this->eventDispatcher->dispatch(OrderEvent::BEFORE_CREATE, new OrderBeforeCreate($customerId, $products));
        $order = new Order();
        /* @var $customer Customer */
        $customer = $this->doctrine->getRepository(Customer::REPOSITORY)->find($customerId);
        $order->setCustomer($customer);
        $addresses = $customer->getAddress();
        $order->setBillingAddress($addresses[0]);
        $order->setShippingAddress($addresses[1]);
        foreach ($products as $product) {
            $this->createProductLine($order, $product['id'], $product['quantity']);
        }
        $this->entityManager->persist($order);
        $this->entityManager->flush();
        $this->eventDispatcher->dispatch(OrderEvent::AFTER_CREATE, new OrderEvent($order));
        $this->entityManager->flush();
        return $order->getId();
    }

    private function createProductLine(Order $order, $productSaleId, $quantity)
    {
        $productLine = new OrderProductLine();
        $productLine->setProductSale($this->doctrine->getRepository(ProductSale::REPOSITORY)->find($productSaleId));
        $productLine->setQuantity($quantity);
        $productLine->setOrder($order);
        $order->addProductLine($productLine);
        $this->entityManager->persist($productLine);
    }

}
