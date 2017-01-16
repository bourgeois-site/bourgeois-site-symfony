<?php
namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class AdminContactsController extends Controller
{
    /**
     * @Route("/админ/контакты", name="admin_contacts")
     */
    public function indexAction()
    {
        return $this->render('admin/contacts/index.html.twig');
    }
}

