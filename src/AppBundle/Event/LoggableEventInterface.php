<?php

namespace AppBundle\Event;

interface LoggableEventInterface
{
    public function getLogContext();
}