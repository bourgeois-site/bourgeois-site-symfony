<?php
namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class WorksController extends Controller
{
    /**
     * @Route("/выполненные-работы/{slug}", name="show_work")
     */
    public function showAction($slug)
    {
        return $this->render('works/show.html.twig', ['service' => $slug]);
    }
}
