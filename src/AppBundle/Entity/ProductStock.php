<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ProductStock
 *
 * @ORM\Table(
 *  name="product_stock",
 *  indexes={
 *      @ORM\Index(name="product_stock_product_id_idx", columns={"product_id"}),
 *      @ORM\Index(name="product_stock_warehouse_id_idx", columns={"warehouse_id"})
 * })
 * @ORM\Entity
 */
class ProductStock
{

    const REPOSITORY = 'AppBundle:ProductStock';

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var Product
     *
     * @ORM\ManyToOne(targetEntity="Product")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="product_id", referencedColumnName="id")
     * })
     */
    private $product;

    /**
     * @var Warehouse
     *
     * @ORM\ManyToOne(targetEntity="Warehouse")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="warehouse_id", referencedColumnName="id")
     * })
     */
    private $warehouse;

    /**
     * @var integer
     *
     * @ORM\Column(name="quantity", type="integer", nullable=true)
     */
    private $quantity;

    /**
     * Set quantity
     *
     * @param integer $quantity
     * @return ProductStock
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     * Get quantity
     *
     * @return integer
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * Set product
     *
     * @param Product $product
     * @return ProductStock
     */
    public function setProduct(Product $product = null)
    {
        $this->product = $product;

        return $this;
    }

    /**
     * Get
     *
     * @return Warehouse
     */
    public function getWarehouse()
    {
        return $this->warehouse;
    }

    /**
     * Set warehouse
     *
     * @param Warehouse $warehouse
     * @return ProductStock
     */
    public function setWarehouse(Warehouse $warehouse = null)
    {
        $this->warehouse = $warehouse;

        return $this;
    }

    /**
     * Get product
     *
     * @return Product
     */
    public function getProduct()
    {
        return $this->product;
    }

}
