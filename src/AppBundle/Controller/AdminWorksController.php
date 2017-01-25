<?php
namespace AppBundle\Controller;

use AppBundle\Form\Type\CategoryType;
use AppBundle\Entity\Category;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

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
     * @Route("/админ/выполненные-работы/новая", name="admin_new_work")
     */
    public function newAction(Request $request)
    {
        $title = "Новая выполненная работа";

        $service = new Category();

        $form = $this->createForm(CategoryType::class, $service);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $category = $form->getData();
            $category->setType('work');
            $category->generateSlug();
            $em = $this->getDoctrine()->getManager();
            $em->persist($category);
            $em->flush();

            $this->addFlash('notice', "Добавлена новая выполненная работа");

            return $this->redirectToRoute('admin_show_work', [
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
     * @Route("/админ/выполненные-работы/{slug}/редактировать", name="admin_show_work")
     */
    public function showAction($slug)
    {
        $work = $this->getDoctrine()->
            getRepository('AppBundle:Category')->
            findOneBy(array('type' => 'work', 'slug' => $slug));

        if (!$work) {
            throw $this->createNotFoundException();
        }

        return $this->render('admin/shared/show_category.html.twig', [
            'category' => $work
        ]);
    }

    /**
     * @Route("/админ/выполненные-работы/{slug}", name="admin_delete_work")
     */
    public function deleteAction($slug)
    {
        $em = $this->getDoctrine()->getManager();
        $work = $em->getRepository('AppBundle:Category')->
            findOneBy(array('type' => 'work', 'slug' =>$slug));

        if (!$work) {
            throw $this->createNotFoundException();
        }

        foreach ($work->getSections() as $section) {
            foreach ($section->getPhotos() as $photo) {
                $em->remove($photo);
            }
            $em->flush();
        }

        $em->remove($work);
        $em->flush();

        $this->addFlash('notice', "'{$work->getTitle()}' удалены из выполненных работ");

        return $this->redirectToRoute('admin_works');
    }
}
