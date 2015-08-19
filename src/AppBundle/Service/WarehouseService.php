<?php

namespace AppBundle\Service;

use AppBundle\Entity\ProductStock;
use AppBundle\Entity\Warehouse;
use AppBundle\Entity\Order;
use AppBundle\Event\Order\OrderEvent;

class WarehouseService extends AbstractDoctrineAware
{

    const ID = 'app.warehouse';

    public function getAll()
    {
        return $this->entityManager
            ->createQueryBuilder()
            ->select('warehouse')
            ->from(Warehouse::REPOSITORY, 'warehouse')
            ->getQuery()
            ->getArrayResult();
    }

    public function getProductStocks($productId)
    {
        $stocks = $this->entityManager
                ->getRepository(ProductStock::REPOSITORY)
                ->findBy(array('product' => $productId));
        if (empty($stocks)) {
            $this->logger->addNotice(sprintf('No stocks found for product %s', $productId));
        }

        return $stocks;
    }

    public function reserveProducts(Order $order)
    {
        if ($this->checkProductsStock($order)){
            $this->doReserveProducts($order);
            $this->eventDispatcher->dispatch(
                OrderEvent::PRODUCTS_RESERVED, new OrderEvent($order)
            );
        } else {
            $this->eventDispatcher->dispatch(
                OrderEvent::PRODUCTS_RESERVATION_FAILED, new OrderEvent($order)
            );
        }
    }

    private function doReserveProducts(Order $order)
    {
    }
    private function checkProductsStock(Order $order)
    {
        $quantities = array();
        foreach ($order->getProductLines() as $productLine) {
            $id = $productLine->getProductSale()->getProduct()->getId();
            $quantities[$id] = $productLine->getQuantity();
        }
        $productStocks = $this
            ->getProductStocksByProductIds(array_keys($quantities))
            ->execute();
        var_dump($quantities);
       // $productStocks = array_unique($productStocks);
        foreach ($productStocks as $productStock) {
            $id = $productStock->getProduct()->getId();
         ///  var_dump('id ='.$id);}die();
         ////  var_dump('q[id]='.$quantities[$id]);
          //  if(isset($quantities[$id])) {
                if ($quantities[$id] <= $productStock->getQuantity()) {
                    unset($quantities[$id]);
                }
         //   }
       }
        return empty($quantities);
    }
    public function getProductStocksByProductIds(array $productIds)
    {
        return $this->entityManager
            ->createQueryBuilder()
            ->select('productStock')
            ->from(ProductStock::REPOSITORY, 'productStock')
            ->where('productStock.product in (:products)')
            ->setParameter('products', $productIds)
            ->getQuery();
    }
    public function packageProducts(Order $order)
    {
        $this->eventDispatcher->dispatch(
            OrderEvent::PACKAGING_START, new OrderEvent($order)
        );
        $this->eventDispatcher->dispatch(
            OrderEvent::PACKAGING_END, new OrderEvent($order)
        );
    }
}
