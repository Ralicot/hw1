<?php

namespace AppBundle\Service\Communication;

use AppBundle\Document\Email;

use AppBundle\Communication\Email\Message;
use AppBundle\Communication\Email\ProviderInterface;
use Doctrine\Bundle\MongoDBBundle\ManagerRegistry;
use Doctrine\ODM\MongoDB\DocumentManager;


class EmailService
{

    const ID = 'app.email';

    private $documentManager;
    private $providers = array();
    private $providerIndex = -1;

    public function __construct(ManagerRegistry $documentManager)
    {
        $this->documentManager = $documentManager->getManager();
    }
    public function addProvider(ProviderInterface $provider)
    {
        $this->providers[] = $provider;
    }

    public function send(Message $message)
    {
        $this->incrementIndex();
        $provider = $this->providers[$this->providerIndex];

        return $provider->send($message);
    }

    private function incrementIndex()
    {
        $this->providerIndex++;
        if ($this->providerIndex > count($this->providers) - 1) {
            $this->providerIndex = 0;
        }
    }
    public function createWarningEmail(Message $message, $status)
    {

        $email = new Email();
        $email->setFrom($message->getFrom());
        $email->setSubject($message->getSubject());
        $email->setStatus($status);
        $email->setBody($message->getMessage());
        var_dump($message->getMessage());
        $this->documentManager->persist($email);
        $this->documentManager->flush($email);
    }

}
