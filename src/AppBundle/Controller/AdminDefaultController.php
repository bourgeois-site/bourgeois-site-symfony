<?php
namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class AdminDefaultController extends Controller
{
    /**
     * @Route("/админ", name="admin_homepage")
     */
    public function indexAction()
    {
        return $this->redirectToRoute('admin_new_requests');
    }

    /**
     * @Route("/админ/о-компании", name="admin_about")
     */
    public function aboutAction()
    {
        return $this->render('admin/default/about.html.twig');
    }

    /**
     * @Route("/админ/профиль", name="admin_profile")
     */
    public function profileAction()
    {
        return $this->render('admin/default/profile.html.twig');
    }

    public function asideAction()
    {
        $newRequestsCount = sizeof(
            $this->getDoctrine()->
            getRepository('AppBundle:Request')->
            findByIsArchived(false)
        );

        return $this->render('admin/partials/aside.html.twig', [
            'newRequestsCount' => $newRequestsCount
        ]);
    }
}
