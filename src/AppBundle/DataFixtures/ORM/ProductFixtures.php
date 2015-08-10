<?php
namespace AppBundle\DataFixtures\ORM;
namespace AppBundle\DataFixtures\ORM;
use AppBundle\Entity\Category;
use AppBundle\Entity\Product;
use AppBundle\Repository\ProductRepository;
use OutOfBoundsException;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
class ProductFixtures extends AbstractDataFixture implements ContainerAwareInterface
{
    private $productsPerCategory;

    private function createAndPersistProducts(Category $category)
    {
        for ($i = 1; $i <= $this->productsPerCategory; $i++) {
            $product = new Product();
            $product->setCode(sprintf('code_%s_%s', $category->getId(), $i))
                ->setTitle(
                    sprintf('title %s %s %s', $category->getId(), $i, uniqid())
                )
                ->setDescription(sprintf('product description %s', $i));
            $category->addProduct($product);
            $this->manager->persist($product);
        }
    }

    protected function createAndPersistData()
    {
        for ($i = 1; true; $i++) {
            try {
                /* @var $category Category */
                $category = $this->getReference(sprintf('category_%s', $i));
                $this->createAndPersistProducts($category);
            } catch (OutOfBoundsException $exception) {
                break;
            }
        }
    }

    protected function preLoad()
    {
        /* @var $productRepository ProductRepository */
        $productRepository = $this->manager->getRepository(Product::REPOSITORY);
        $productRepository->deleteAll();
    }

    public function setContainer(ContainerInterface $container = null)
    {
        $fixturesConfig = $container->getParameter('fixtures');
        $this->productsPerCategory = $fixturesConfig['products_per_category'];
    }

    public function getOrder()
    {
        {
            return 2;
        }
    }
}