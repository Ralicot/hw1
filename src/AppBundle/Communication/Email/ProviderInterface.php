<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 18.08.2015
 * Time: 14:17
 */

namespace AppBundle\Communication\Email;


class ProviderInterface
{
    public function send(Message $message);
}