<?php
namespace JsonRpcBundle\Controller;

use JsonRpcBundle\Server;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class ServerController extends Controller
{
    public function handleAction(Request $request, $service)
    {
        $loggingEnabled = $this->getParameter('enable_json_logging');
        $logger = $this->get('monolog.logger.jsonrpc');

        if($loggingEnabled == 'on') {
            $logger->info('REQUEST: ' . $request);
        }

        $server = $this->get(Server::ID);
        $result = $server->handle($request->getContent(), $service);

        if($loggingEnabled == 'on') {
            $logger->info("RESPONSE: " . json_encode($result->toArray()));
        }
        return new JsonResponse($result->toArray());
    }
}