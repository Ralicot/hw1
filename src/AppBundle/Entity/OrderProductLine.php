<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * OrderProductLine
 *
 * @ORM\Table(name="order_product_line", indexes={@ORM\Index(name="fk_order_product_line_order_idx", columns={"order_id"}), @ORM\Index(name="fk_order_product_line_product_sale_idx", columns={"product_sale_id"})})
 * @ORM\Entity
 */
class OrderProductLine
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var integer
     *
     * @ORM\Column(name="quantity", type="integer", nullable=true)
     */
    private $quantity;

    /**
     * @var \Order
     *
     * @ORM\ManyToOne(targetEntity="Order")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="order_id", referencedColumnName="id")
     * })
     */
    private $order;

    /**
     * @var \ProductSale
     *
     * @ORM\ManyToOne(targetEntity="ProductSale")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="product_sale_id", referencedColumnName="id")
     * })
     */
    private $productSale;



    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set quantity
     *
     * @param integer $quantity
     * @return OrderProductLine
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
     * Set order
     *
     * @param \AppBundle\Entity\Order $order
     * @return OrderProductLine
     */
    public function setOrder(\AppBundle\Entity\Order $order = null)
    {
        $this->order = $order;

        return $this;
    }

    /**
     * Get order
     *
     * @return \AppBundle\Entity\Order 
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * Set productSale
     *
     * @param \AppBundle\Entity\ProductSale $productSale
     * @return OrderProductLine
     */
    public function setProductSale(\AppBundle\Entity\ProductSale $productSale = null)
    {
        $this->productSale = $productSale;

        return $this;
    }

    /**
     * Get productSale
     *
     * @return \AppBundle\Entity\ProductSale 
     */
    public function getProductSale()
    {
        return $this->productSale;
    }
}
