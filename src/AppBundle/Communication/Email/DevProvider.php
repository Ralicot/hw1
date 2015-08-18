<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 18.08.2015
 * Time: 14:18
 */

namespace AppBundle\Communication\Email;


class DevProvider implements ProviderInterface
{
    public function send(Message $message)
    {
        return true;

    }
}