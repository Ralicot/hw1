<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 06.08.2015
 * Time: 15:33
 */

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DashboardController extends Controller
{
    public function indexAction()
    {
        $entities = array(
            'account',
            'address',
            'category',
            'contact',
            'country',
            'customer',
            'order',
            'orderproductline',
            'productacquisition',
            'product',
            'productsale',
            'vendor',
            'warehouse'
        );

        return $this->render('AppBundle::dashboard.html.twig', array('entities' => $entities));
    }

}