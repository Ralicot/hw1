<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 18.08.2015
 * Time: 14:18
 */

namespace AppBundle\Communication\Email;


class PhpProvider implements  ProviderInterface
{

    public function send(Message $message)
    {
        return mail(
            $message->getTo(),
            $message->getSubject(),
            $message->getMessage(),
            $message->getAdditionalHeaders(),
            $message->getAdditionalParameters()
        );
    }
}