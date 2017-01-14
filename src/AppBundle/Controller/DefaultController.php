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
        $services = $this->getDoctrine()->
            getRepository('AppBundle:Category')->
            findByType('service');

        $works = $this->getDoctrine()->
            getRepository('AppBundle:Category')->
            findByType('work');

        return $this->render('default/index.html.twig', [
            'services' => $services,
            'works' => $works
        ]);
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

    public function headerAction()
    {
        $services = $this->getDoctrine()->
            getRepository('AppBundle:Category')->
            findByType('service');
        $works = $this->getDoctrine()->
            getRepository('AppBundle:Category')->
            findByType('work');

        return $this->render('partials/header.html.twig', [
            'services' => $services,
            'works' => $works
        ]);
    }

    public function footerAction()
    {
        $company = [
            "О компании" => $this->generateUrl('aboutpage'),
            "Цены" => $this->generateUrl('prices')
        ];

        $services = $this->getDoctrine()->
            getRepository('AppBundle:Category')->
            findByType('service');

        $works = $this->getDoctrine()->
            getRepository('AppBundle:Category')->
            findByType('work');

        $email_contacts = $this->getDoctrine()->
            getRepository('AppBundle:InternetContact')->
            findByIsEmail(1);

        $social_contacts = $this->getDoctrine()->
            getRepository('AppBundle:InternetContact')->
            findByIsEmail(0);

        $real_contacts = $this->getDoctrine()->
            getRepository('AppBundle:RealContact')->
            findAll();

        return $this->render('partials/footer.html.twig', [
            'company' => $company,
            'services' => $services,
            'works' => $works,
            'email_contacts' => $email_contacts,
            'social_contacts' => $social_contacts,
            'real_contacts' => $real_contacts
        ]);
    }
}
