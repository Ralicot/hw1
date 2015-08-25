<?php
namespace AppBundle\Event\Document;
use AppBundle\Document\Document;
use AppBundle\Event\LoggableEventInterface;
use Symfony\Component\EventDispatcher\Event;
class DocumentEvent extends Event implements LoggableEventInterface
{
    const INVOICE_GENERATE_START = 'document.invoice.generate.start';
    const INVOICE_GENERATE_FINISH = 'document.invoice.generate.finish';
    /**
     *
     * @var Document
     */
    private $document;
    public function __construct(Document $document) {
        $this->document = $document;
    }
    public function getLogContext() {
        return array(
            'documentId' => $this->document->getId(),
            'orderNumber' => $this->document->getOrderNumber()
        );
    }
}