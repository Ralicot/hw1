<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DevController extends Controller
{
    public function showAction()
    {
        return $this->render(phpinfo());
    }
}
