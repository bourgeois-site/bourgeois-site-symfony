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
        $service = $this->getDoctrine()->
            getRepository('AppBundle:Category')->
            findOneBySlug($slug);

        if (!$service) {
            throw $this->createNotFoundException();
        }

        $title = $service->getTitle();

        $sections = $service->getSections();

        return $this->render('services/show.html.twig',
            ['title' => $title, 'sections' => $sections]);
    }
}
