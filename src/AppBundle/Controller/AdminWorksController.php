<?php
namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class AdminWorksController extends Controller
{
    /**
     * @Route("/админ/выполненные-работы", name="admin_works")
     */
    public function indexAction()
    {
        $works = $this->getDoctrine()->
            getRepository('AppBundle:Category')->
            findByType('work');

        return $this->render('admin/works/index.html.twig', [
            'works' => $works
        ]);
    }

    /**
     * @Route("/админ/выполненные-работы/редактировать/{slug}", name="admin_edit_work")
     */
    public function editAction($slug)
    {
        $work = $this->getDoctrine()->
            getRepository('AppBundle:Category')->
            findOneBySlug($slug);

        if (!$work) {
            throw $this->createNotFoundException();
        }

        return $this->render('admin/works/edit.html.twig', [
            'work' => $work
        ]);
    }
}
