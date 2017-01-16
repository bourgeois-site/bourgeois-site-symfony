<?php
namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class AdminRequestsController extends Controller
{
    /**
     * @Route("/админ/заявки/новые", name="admin_new_requests")
     */
    public function indexNewAction()
    {
        $requests = $this->getDoctrine()->
            getRepository('AppBundle:Request')->
            findByIsArchived(false, array('createdAt' => 'desc'));

        return $this->render('admin/requests/index.html.twig',
            ['type' => 'Новые', 'requests' => $requests]);
    }

    /**
     * @Route("/админ/заявки/архив", name="admin_archived_requests")
     */
    public function indexArchivedAction()
    {
        $requests = $this->getDoctrine()->
            getRepository('AppBundle:Request')->
            findByIsArchived(true, array('createdAt' => 'desc'));

        return $this->render('admin/requests/index.html.twig',
            ['type' => 'Архивированные', 'requests' => $requests]);
    }
}
