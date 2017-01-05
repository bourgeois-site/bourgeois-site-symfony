<?php
namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction()
    {
        $aboutCategory = $this->getDoctrine()->
            getRepository('AppBundle:Category')->
            findOneByType('about');
        $about = $this->getDoctrine()->
            getRepository('AppBundle:Section')->
            findOneByCategory($aboutCategory)->getTitle();

        return $this->render('default/index.html.twig', ['about' => $about]);
    }

    /**
     * @Route("/о-компании", name="aboutpage")
     */
    public function aboutAction()
    {
        $category = $this->getDoctrine()->
            getRepository('AppBundle:Category')->
            findOneByType('about');
        $title = $category->getTitle();
        $sections = $category->getSections();

        return $this->render('default/about.html.twig',
            ['title' => $title, 'sections' => $sections]);
    }
}
