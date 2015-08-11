<?php
namespace AppBundle\Service;

use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\ORM\EntityManager;
use Symfony\Bridge\Monolog\Logger;

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
    public function __construct(Registry $doctrine, Logger $logger = null)
    {
        $this->doctrine = $doctrine;
        $this->entityManager = $doctrine->getManager();
        if ($logger) {
            $this->logger = $logger;
        } else {
            $this->logger = new Logger('');
        }
    }
}