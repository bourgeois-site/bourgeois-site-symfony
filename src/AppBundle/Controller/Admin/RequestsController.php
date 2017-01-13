<?php
namespace AppBundle\Controller\Admin;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

// TODO: pagination, active links, archive possibility
class RequestsController extends Controller
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
            ['type' => 'новые', 'requests' => $requests]);
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
            ['type' => 'архив', 'requests' => $requests]);
    }
}
