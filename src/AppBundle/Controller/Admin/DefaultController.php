<?php
namespace AppBundle\Controller\Admin;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/администратор", name="admin_homepage")
     */
    public function indexAction()
    {
        return $this->render('admin/default/index.html.twig');
    }
}
