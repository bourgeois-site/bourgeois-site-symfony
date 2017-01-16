<?php
namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class AdminServicesController extends Controller
{
    /**
     * @Route("/админ/услуги", name="admin_services")
     */
    public function indexAction()
    {
        return $this->render('admin/services/index.html.twig');
    }
}
