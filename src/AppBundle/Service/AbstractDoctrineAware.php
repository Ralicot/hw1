<?php

namespace AppBundle\Service;

use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\ORM\EntityManager;
use Symfony\Bridge\Monolog\Logger;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class AbstractDoctrineAware
{

    const ID = 'app.doctrine_aware';

    /**
     *
     * @var Registry
     */
    protected $doctrine;

    /**
     *
     * @var EntityManager
     */
    protected $entityManager;

    /**
     *
     * @var Logger
     */
    protected $logger;

    /**
     *
     * @var EventDispatcherInterface
     */
    protected $eventDispatcher;

    public function __construct(Registry $doctrine, Logger $logger, EventDispatcherInterface $eventDispatcher)
    {
        $this->doctrine = $doctrine;
        $this->entityManager = $doctrine->getManager();
        $this->logger = $logger;
        $this->eventDispatcher = $eventDispatcher;

    }

}
