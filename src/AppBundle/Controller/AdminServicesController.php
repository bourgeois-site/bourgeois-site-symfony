<?php
namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class AdminServicesController extends Controller
{
    /**
     * @Route("/админ/услуги", name="admin_services")
     */
    public function indexAction()
    {
        $services = $this->getDoctrine()->
            getRepository('AppBundle:Category')->
            findByType('service');

        return $this->render('admin/services/index.html.twig', [
            'services' => $services
        ]);
    }

    /**
     * @Route("/админ/услуги/редактировать/{slug}", name="admin_edit_service")
     */
    public function editAction($slug)
    {
        $service = $this->getDoctrine()->
            getRepository('AppBundle:Category')->
            findOneBySlug($slug);

        if (!$service) {
            throw $this->createNotFoundException();
        }

        return $this->render('admin/services/edit.html.twig', [
            'service' => $service
        ]);
    }
}
