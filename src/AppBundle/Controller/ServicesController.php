<?php
namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class ServicesController extends Controller
{
    /**
     * @Route("/услуги/{slug}", name="show_service")
     */
    public function showAction($slug)
    {
        return $this->render('services/show.html.twig', ['service' => $slug]);
    }
}
