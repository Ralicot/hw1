<?php

namespace AppBundle\Service;

use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class JsonRpcServer
{

    private $functions;
    private $services;
    const METHOD_NOT_FOUND = 33322;

    public function __construct()
    {

    }

    public function execute (Request $httpreq)
    {
        $json = $httpreq->getContent();
        $request = json_decode($json,true);
        $requestId = (isset($request['id'])) ? $request['id'] : null);


         if (in_array($request['method'], array_keys($this->functions))) {
             $servicename = $this->functions[$request['method']]['service'];
             $method = $this->functions[$request['method']]['method'];
         } else {
             if (count($this->services) && strpos($request['method'], ':') > 0) {
                 list($servicename, $method) = explode(':', $request['method']);
                 if (!in_array($servicename, $this->services)) {
                     return $this->getErrorResponse(self::METHOD_NOT_FOUND, $requestId);
                 }
             } else {
                 return $this->getErrorResponse(self::METHOD_NOT_FOUND, $requestId);
             }
         }

    }
    protected function getError($code)
    {
        $message = '';
        switch ($code) {
            case self::PARSE_ERROR:
                $message = 'Parse error';
                break;
            case self::INVALID_REQUEST:
                $message = 'Invalid request';
                break;
            case self::METHOD_NOT_FOUND:
                $message = 'Method not found';
                break;
            case self::INVALID_PARAMS:
                $message = 'Invalid params';
                break;
            case self::INTERNAL_ERROR:
                $message = 'Internal error';
                break;
        }
        return array('code' => $code, 'message' => $message);
    }


}