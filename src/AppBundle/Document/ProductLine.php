<?php
namespace AppBundle\Document;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
/**
 * @MongoDB\EmbeddedDocument
 */
class ProductLine
{
    /**
     * @MongoDB\String
     */
    private $code;
    /**
     * @MongoDB\String
     */
    private $title;
    /**
     * @MongoDB\Integer
     */
    private $quantity;/**
 * @MongoDB\Integer
 */
    private $price;
    /**
     * @MongoDB\Integer
     */
    private $total;
    public function getCode() {
        return $this->code;
    }
    public function getTitle() {
        return $this->title;
    }
    public function getQuantity() {
        return $this->quantity;
    }
    public function getPrice() {
        return $this->price;
    }
    public function getTotal() {
        return $this->total;
    }
    public function setCode($code) {
        $this->code = $code;
        return $this;
    }
    public function setTitle($title) {
        $this->title = $title;
        return $this;
    }
    public function setQuantity($quantity) {
        $this->quantity = $quantity;
        return $this;
    }
    public function setPrice($price) {
        $this->price = $price;
        return $this;
    }
    public function setTotal($total) {
        $this->total = $total;
        return $this;
    }
}