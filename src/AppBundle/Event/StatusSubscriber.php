<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 19.08.2015
 * Time: 15:33
 */

namespace AppBundle\Event;

use AppBundle\Event\Order\OrderBeforeCreate;
use AppBundle\Entity\Order;
use JsonRpcBundle\Logger;
use Symfony\Component\HttpKernel\Log\LoggerInterface;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
// for Doctrine 2.4: Doctrine\Common\Persistence\Event\LifecycleEventArgs;

class StatusSubscriber implements EventSubscriber
{
    private $oldStatus;
    private $newStatus;
    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
        $this->oldStatus = "NONE";
        $this->newStatus = "PRE CREATE";
    }


    public function getSubscribedEvents()
    {
        return array('prePersist', 'preUpdate');
    }
    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        var_dump('ok');
//        if($entity instanceof Order)
            $this->logger->addInfo('STATUS: '.$entity->getStatus());
    }
    public function preUpdate(LifecycleEventArgs $args)
    {
        var_dump('---');
        $entity = $args->getEntity();
//        if($entity instanceof Order)
            $this->logger->addInfo('STATUS_UPDATED: '.$entity->getStatus());
    }
}