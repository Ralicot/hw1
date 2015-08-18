<?php


class JsonRpcListener
{

    protected $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger=$logger;
    }
}