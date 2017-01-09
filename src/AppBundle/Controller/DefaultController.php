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
        $about = $this->getDoctrine()->
            getRepository('AppBundle:Category')->
            findOneByType('landing')->getTitle();

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

    /**
     * @Route("/контакты", name="contacts")
     */
    public function contactsAction()
    {
        $title = "Контакты";

        $real_contacts = $this->getDoctrine()->
            getRepository('AppBundle:RealContact')->
            findAll();

        $email_contacts = $this->getDoctrine()->
            getRepository('AppBundle:InternetContact')->
            findByIsEmail(1);

        $social_contacts = $this->getDoctrine()->
            getRepository('AppBundle:InternetContact')->
            findByIsEmail(0);

        return $this->render('default/contacts.html.twig', [
            'title' => $title,
            'real_contacts' => $real_contacts,
            'email_contacts' => $email_contacts,
            'social_contacts' => $social_contacts
        ]);
    }
}
