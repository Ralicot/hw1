<?php
namespace AppBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * @MongoDB\Document(db="app", collection="documents")
 * @MongoDB\HasLifecycleCallbacks
 */
class Document
{
    const REPOSITORY = 'AppBundle:Document';
    /**
     * @MongoDB\Id
     */
    private $id;
    /**
     * @MongoDB\String
     */
    private $type;
    /**
     * @MongoDB\Increment
     */
    private $number;
    /**
     * @MongoDB\Integer
     */
    private $orderNumber;
    /**
     * @var \DateTime
     * @MongoDB\Date
     */
    private $createDate;
    /**
     * @MongoDB\String
     */
    private $customerName;
    /**
     * @MongoDB\String
     */
    private $billingAddress;
    /**
     * @MongoDB\Bin
     */
    private $bodyPdf;
    /**
     * @MongoDB\String
     */
    private $bodyHtml;
    /**
     * @MongoDB\String
     */
    private $currency;
    /**
     * @MongoDB\Integer
     */
    private $total;
    /**
     *
     * @var ProductLine[]
     * @MongoDB\EmbedMany(targetDocument="ProductLine")
     */
    private $productLines = array();

    public function getId()
    {
        return $this->id;
    }

    public function getType()
    {
        return $this->type;
    }

    public function getNumber()
    {
        return $this->number;
    }

    public function getOrderNumber()
    {
        return $this->orderNumber;
    }

    public function getCreateDate()
    {
        return $this->createDate;
    }

    public function getCustomerName()
    {
        return $this->customerName;
    }


    function getBillingAddress()
    {
        return $this->billingAddress;
    }

    public
    function getBodyPdf()
    {
        return $this->bodyPdf;
    }

    public
    function getBodyHtml()
    {
        return $this->bodyHtml;
    }

    public
    function getCurrency()
    {
        return $this->currency;
    }

    public
    function getTotal()
    {
        return $this->total;
    }

    public
    function getProductLines()
    {
        return $this->productLines;
    }

    public
    function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    public
    function setOrderNumber($orderNumber)
    {
        $this->orderNumber = $orderNumber;
        return $this;
    }

    public
    function setCustomerName($customerName)
    {
        $this->customerName = $customerName;
        return $this;
    }

    public
    function setBillingAddress($billingAddress)
    {
        $this->billingAddress = $billingAddress;
        return $this;
    }

    public
    function setBodyPdf($bodyPdf)
    {
        $this->bodyPdf = $bodyPdf;
        return $this;
    }

    public
    function setBodyHtml($bodyHtml)
    {
        $this->bodyHtml = $bodyHtml;
        return $this;
    }

    public
    function setCurrency($currency)
    {
        $this->currency = $currency;
        return $this;
    }

    public
    function setTotal($total)
    {
        $this->total = $total;
        return $this;
    }

    public
    function setProductLines(array $productLines)
    {
        $this->productLines = $productLines;
        return $this;
    }

    public
    function addProductLine(ProductLine $productLine)
    {
        $this->productLines[] = $productLine;
    }

    /**
     * @MongoDB\PrePersist
     */
    public
    function prePersist()
    {
        $this->createDate = new \DateTime();
    }
}