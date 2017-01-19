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
     * @Route("/админ/услуги/{slug}/редактировать", name="admin_edit_service")
     */
    public function editAction($slug)
    {
        $service = $this->getDoctrine()->
            getRepository('AppBundle:Category')->
            findOneBy(array('type' => 'service', 'slug' => $slug));

        if (!$service) {
            throw $this->createNotFoundException();
        }

        return $this->render('admin/services/edit.html.twig', [
            'service' => $service
        ]);
    }

    /**
     * @Route("/админ/услуги/{slug}", name="admin_delete_service")
     */
    public function deleteAction($slug)
    {
        $em = $this->getDoctrine()->getManager();
        $service = $em->getRepository('AppBundle:Category')->
            findOneBy(array('type' => 'service', 'slug' =>$slug));

        if (!$service) {
            throw $this->createNotFoundException();
        }

        foreach ($service->getSections() as $section) {
            foreach ($section->getPhotos() as $photo) {
                $em->remove($photo);
            }
            $em->flush();
        }

        $em->remove($service);
        $em->flush();

        $this->addFlash('notice', "Услуга {$service->getTitle()} удалена");

        return $this->redirectToRoute('admin_services');
    }
}
