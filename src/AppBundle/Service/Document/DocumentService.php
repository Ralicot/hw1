<?php

namespace AppBundle\Service\Document;

use AppBundle\Document\Document;
use AppBundle\Document\ProductLine;
use AppBundle\Entity\Order;
use AppBundle\Event\Order\OrderEvent;
use Doctrine\Bundle\MongoDBBundle\ManagerRegistry;
use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Process\Process;
use Symfony\Component\Templating\EngineInterface as Templating;
use AppBundle\Exception\Document\DocumentNotFoundException;
use AppBundle\Event\Document\DocumentEvent;
use OldSound\RabbitMqBundle\RabbitMq\Producer;
use PhpAmqpLib\Message\AMQPMessage;

class DocumentService
{
    const ID = 'app.document';
    /**
     *
     * @var Templating
     */
    private $twigEngine;
    /**
     *
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;
    /**
     *
     * @var DocumentManager
     */
    private $documentManager;

/**
*
* @var Producer
*/
    private $documentProducer;
    public function __construct(
        Templating $twigEngine,
        EventDispatcherInterface $eventDispatcher,
        ManagerRegistry $documentManager,
    Producer $documentProducer
    ) {
        $this->twigEngine = $twigEngine;
        $this->eventDispatcher = $eventDispatcher;
        $this->documentManager = $documentManager->getManager();
        $this->documentProducer = $documentProducer;
    }
    public function generateInvoice(Order $order) {
        $document = $this->createDocumentFromOrder($order);
        $this->eventDispatcher->dispatch(
            DocumentEvent::INVOICE_GENERATE_START, new DocumentEvent($document)
        );
        $this->documentManager->persist($document);
        $this->documentManager->flush();
        $this->documentProducer->publish($document->getId(), 'document.invoice');
    }
    private function createDocumentFromOrder(Order $order) {
        $document = new Document();
        $billingAddress = $order->getBillingAddress();
        $productLines = $this->createProductLinesFromOrder($order);
        $total = 0;
        foreach ($productLines as $productLine) {
            $total += $productLine->getTotal();
        }
        $document->setBillingAddress($billingAddress->getName())
            ->setCurrency($billingAddress->getCountry()->getCurrency())
            ->setCustomerName($order->getCustomer()->getContact()->getName())
            ->setOrderNumber($order->getId())
            ->setProductLines($productLines)
            ->setTotal($total)
            ->setType('invoice');
        return $document;
    }
    private function createProductLinesFromOrder(Order $order) {
        $productLines = array();
        foreach ($order->getProductLines() as $orderProductLine) {
            $code = $orderProductLine->getProductSale()->getProduct()->getCode();
            $title = $orderProductLine->getProductSale()->getProduct()->getTitle();
            $price = $orderProductLine->getProductSale()->getPrice();
            $quantity = $orderProductLine->getQuantity();
            $productLine = new ProductLine();
            $productLine->setCode($code)
                ->setPrice($price)
                ->setQuantity($quantity)
                ->setTitle($title)
                ->setTotal($quantity * $price);
            $productLines[] = $productLine;
        }
        return $productLines;
    }
    private function convertHtmlToPdf($html) {
        $process = new Process(sprintf("echo '%s' | wkhtmltopdf - -", $html));
        $process->run();
        return $process->getOutput();
    }

    public function getInvoiceHtml($orderNumber) {
        $repository = $this->documentManager->getRepository(Document::REPOSITORY);
        $document = $repository->findOneBy(
            array('type' => 'invoice', 'orderNumber' => (int) $orderNumber)
        );
        if (!$document) {
            throw new DocumentNotFoundException();
        }
        return $document->getBodyHtml();
    }

    public function execute(AMQPMessage $amqpMessage) {
        $documentId = $amqpMessage->body;
        $repository = $this->documentManager->getRepository(Document::REPOSITORY);
        $document = $repository->find($documentId);
        $templateName = 'AppBundle:Document:invoice.html.twig';
        $html = $this->twigEngine->render($templateName, array('document' => $document));
        $document->setBodyHtml($html);
        $document->setBodyPdf($this->convertHtmlToPdf($html));
        $this->documentManager->flush();
        $this->eventDispatcher->dispatch(
            DocumentEvent::INVOICE_GENERATE_FINISH, new DocumentEvent($document)
        );
        return true;
    }
}