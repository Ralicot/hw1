<?php

namespace AppBundle\EventListener;

use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use AppBundle\Service;

class UserRequestListener
{

    private $service;

    public function onKernelRequest(GetResponseEvent $event)
    {

        $request = $event->getRequest();
        $response = $this->service->execute($request);


        if ($request->getContentType() == 'json') {
           $event->setResponse(new Response($response));
        }


    }

    public function setService($service) {
        $this->service = $service;

    }




}