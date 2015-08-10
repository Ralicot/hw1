<?php
namespace AppBundle\Service;
use AppBundle\Entity\Category;
class CatalogService extends AbstractDoctrineAware
{
    const ID = 'app.catalog';
    public function getCategories()
    {
        return $this->entityManager
            ->getRepository(Category::REPOSITORY)
            ->findAll();
    }
}