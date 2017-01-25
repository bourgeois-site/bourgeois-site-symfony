<?php
namespace AppBundle\Controller;

use AppBundle\Form\Type\CategoryType;
use AppBundle\Entity\Category;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

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
     * @Route("/админ/услуги/новая", name="admin_new_service")
     */
    public function newAction(Request $request)
    {
        $title = "Новая услуга";

        $service = new Category();

        $form = $this->createForm(CategoryType::class, $service);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $category = $form->getData();
            $category->setType('service');
            $category->generateSlug();
            $em = $this->getDoctrine()->getManager();
            $em->persist($category);
            $em->flush();

            $this->addFlash('notice', "Добавлена новая услуга");

            return $this->redirectToRoute('admin_show_service', [
                'slug' => $category->getSlug()
            ]);
        }

        return $this->render('admin/shared/new_category.html.twig', [
            'title' => $title,
            'category' => $service,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/админ/услуги/{slug}/редактировать", name="admin_show_service")
     */
    public function showAction($slug)
    {
        $service = $this->getDoctrine()->
            getRepository('AppBundle:Category')->
            findOneBy(array('type' => 'service', 'slug' => $slug));

        if (!$service) {
            throw $this->createNotFoundException();
        }

        return $this->render('admin/shared/show_category.html.twig', [
            'category' => $service
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

        $this->addFlash('notice', "Услуга \"{$service->getTitle()}\" удалена");

        return $this->redirectToRoute('admin_services');
    }
}
