<?php

namespace Acme\DemoBundle\EventListener;

use \Symfony\Component\HttpKernel\Event\GetResponseEvent;
use \Symfony\Component\HttpFoundation\Response;

class UserRequestListener implements EventSubscriberInterface
{
public function onKernelRequest(GetResponseEvent $event)
{
    die('it works');
$request = $event->getRequest();
/* @var $request \Symfony\Component\HttpFoundation\Request */

if ($request->getRequestFormat() == 'json') {
$event->setResponse(new Response('We have no response for a JSON request', 501));
}
}
}