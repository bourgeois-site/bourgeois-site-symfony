<?php
namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class AdminWorksController extends Controller
{
    /**
     * @Route("/админ/выполненные-работы", name="admin_works")
     */
    public function indexAction()
    {
        return $this->render('admin/works/index.html.twig');
    }
}
