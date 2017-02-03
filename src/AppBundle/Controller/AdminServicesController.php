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

        $help = "<p>Здесь можно создать/просмотреть/удалить услуги.</p>
            <p>Миниатюры под заголовками услуг те же, что и на главной странице сайта</p>";

        return $this->render('admin/services/index.html.twig', [
            'services' => $services,
            'help' => $help
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
            $service = $form->getData();
            $service->setType('service');
            $service->generateSlug();
            $em = $this->getDoctrine()->getManager();
            $em->persist($service);
            $em->flush();

            $this->addFlash('notice', "Добавлена новая услуга");

            return $this->redirectToRoute('admin_show_service', [
                'slug' => $service->getSlug()
            ]);
        }

        $help = "<p>Заголовок и фото будут использоваться на главной странице сайта</p>
            <p>После создания можно будет добавлять разделы и редактировать эту услугу</p>";

        return $this->render('admin/shared/new_category.html.twig', [
            'title' => $title,
            'category' => $service,
            'form' => $form->createView(),
            'help' => $help
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

        $help = "<p>Заголовок и фото главной формы (значок справа от \"{$service->getTitle()}\") будут использоваться на главной странице сайта.</p>
            <p>Описание услуги состоит из разделов (абзацев), к которым можно прикреплять фото.</p>
            <p>Количество абзацев и прикрепленных к ним фото не ограничено.</p>
            <p>Каждое фото можно дополнить заголовком и описанием.</p>
            <p>При удалении абзаца все прикрепленные к нему фото удаляются.</p>";

        return $this->render('admin/shared/show_category.html.twig', [
            'category' => $service,
            'help' => $help
        ]);
    }

    /**
     * @Route("/админ/услуги/{slug}/удалить", name="admin_delete_service")
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
