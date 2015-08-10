<?php
namespace AppBundle\Service;
use AppBundle\Entity\ProductStock;
use AppBundle\Entity\Warehouse;
class WarehouseService extends AbstractDoctrineAware
{
    const ID = 'app.warehouse';
    public function getAll()
    {
        return $this->entityManager
            ->getRepository(Warehouse::REPOSITORY)
            ->findAll();
    }
    public function getProductStocks($productId)
    {
        $stocks = $this->entityManager->
        getRepository(ProductStock::REPOSITORY)
            ->findBy(array('product' => $productId));
        if (empty($stocks)) {
            $this->logger->addNotice(
                sprintf('No stocks found for product %s', $productId)
            );
        }
        return $stocks;
    }
    public function moveProductStock($productId, $quantity, $fromWarehouseId, $toWarehouseId)
    {

    }
}