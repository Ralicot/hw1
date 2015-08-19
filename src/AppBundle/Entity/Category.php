<?php

namespace AppBundle\Entity;

use AppBundle\Exception\LogicException;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Category
 *
 * @ORM\Table(name="category", indexes={@ORM\Index(name="fk_category_category_idx", columns={"parent_category_id"})})
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CategoryRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Category
{

    const REPOSITORY = 'AppBundle:Category';

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="label", type="string", length=45, nullable=true)
     * @Assert\NotBlank(message="The label cannot be empty")
     */
    private $label;

    /**
     * @var \Category
     *
     * @ORM\ManyToOne(targetEntity="Category")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="parent_category_id", referencedColumnName="id")
     * })
     */
    private $parentCategory;

    /**
    * @var \Doctrine\Common\Collections\Collection
    *
    * @ORM\ManyToMany(targetEntity="Product", inversedBy="category")
    * @ORM\JoinTable(name="category_has_product",
    *   joinColumns={
    *     @ORM\JoinColumn(name="category_id", referencedColumnName="id", onDelete="cascade")
    *   },
    *   inverseJoinColumns={
    *     @ORM\JoinColumn(name="product_id", referencedColumnName="id", onDelete="cascade")
    *   }
    * )
    */
   private $product;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->product = new \Doctrine\Common\Collections\ArrayCollection();
    }

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
     * Set label
     *
     * @param string $label
     * @return Category
     */
    public function setLabel($label)
    {
        $this->label = $label;

        return $this;
    }

    /**
     * Get label
     *
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * Set parentCategory
     *
     * @param \AppBundle\Entity\Category $parentCategory
     * @return Category
     */
    public function setParentCategory(\AppBundle\Entity\Category $parentCategory = null)
    {
        $this->parentCategory = $parentCategory;

        return $this;
    }

    /**
     * Get parentCategory
     *
     * @return \AppBundle\Entity\Category
     */
    public function getParentCategory()
    {
        return $this->parentCategory;
    }

    /**
     * Add product
     *
     * @param \AppBundle\Entity\Product $product
     * @return Category
     */
    public function addProduct(\AppBundle\Entity\Product $product)
    {
        $this->product[] = $product;

        return $this;
    }

    /**
     * Remove product
     *
     * @param \AppBundle\Entity\Product $product
     */
    public function removeProduct(\AppBundle\Entity\Product $product)
    {
        $this->product->removeElement($product);
    }

    /**
     * Get product
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getProduct()
    {
        return $this->product;
    }

    public function __toString()
    {
        return $this->getLabel();
    }

    /**
     * @Assert\IsTrue(message = "Parent category cannot be the same as child")
     */
    public function isNotSameAsParent()
    {
        if (!$this->getParentCategory()) {
            return true;
        }
        return $this->getId() !== $this->getParentCategory()->getId();
    }

    /**
     * @ORM\PreRemove
     */
    public function preRemove()
    {
        if (count($this->getProduct())) {
            throw new LogicException('Cannot remove a category that has products');
        }
    }
}
