<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 19.08.2015
 * Time: 09:34
 */

namespace JsonRpcBundle;

use Symfony\Bridge\Monolog\Logger as LoggerBridge;

class Logger
{
    const ID = 'json_rpc.logger';

    private $logger;

    public function __construct(LoggerBridge $logger = null)
    {
        if (!$logger) {
            $logger = new LoggerBridge();
        }
        $this->logger = $logger;
    }
    public function getLogger()
    {
        return $this->logger;
    }

}