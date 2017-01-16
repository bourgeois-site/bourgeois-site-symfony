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
        $work = $this->getDoctrine()->
            getRepository('AppBundle:Category')->
            findOneBySlug($slug);

        if (!$work) {
            throw $this->CreateNotFoundException();
        }

        $title = $work->getTitle();

        $sections = $work->getSections();

        return $this->render('works/show.html.twig',
            ['title' => $title, 'sections' => $sections]);
    }
}
