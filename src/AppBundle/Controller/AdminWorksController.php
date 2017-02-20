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
     * @Route("/админ/портфолио", name="admin_works")
     */
    public function indexAction()
    {
        $works = $this->getDoctrine()->
            getRepository('AppBundle:Category')->
            findByType('work');

        $help = "<p>Здесь можно создать/просмотреть/удалить выполненные работы.</p>
            <p>Миниатюры под заголовками работ те же, что и на главной странице сайта</p>";

        return $this->render('admin/works/index.html.twig', [
            'works' => $works,
            'help' => $help
        ]);
    }

    /**
     * @Route("/админ/портфолио/новая-работа", name="admin_new_work")
     */
    public function newAction(Request $request)
    {
        $title = "Новая работа";

        $work = new Category();

        $form = $this->createForm(CategoryType::class, $work);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $work = $form->getData();
            $work->setType('work');
            $work->generateSlug();
            $em = $this->getDoctrine()->getManager();
            $em->persist($work);
            $em->flush();

            $this->addFlash('notice', "Добавлена новая работа");

            return $this->redirectToRoute('admin_show_work', [
                'slug' => $work->getSlug()
            ]);
        }

        $help = "<p>Заголовок и фото будут использоваться на главной странице сайта</p>
            <p>После создания можно будет добавлять разделы и редактировать эту работу</p>";

        return $this->render('admin/shared/new_category.html.twig', [
            'title' => $title,
            'category' => $work,
            'form' => $form->createView(),
            'help' => $help
        ]);
    }

    /**
     * @Route("/админ/портфолио/{slug}/редактировать", name="admin_show_work")
     */
    public function showAction($slug)
    {
        $work = $this->getDoctrine()->
            getRepository('AppBundle:Category')->
            findOneBy(array('type' => 'work', 'slug' => $slug));

        if (!$work) {
            throw $this->createNotFoundException();
        }

        $help = "<p>Заголовок и фото главной формы (значок справа от \"{$work->getTitle()}\") будут использоваться на главной странице сайта.</p>
            <p>Описание работы состоит из разделов (абзацев), к которым можно прикреплять фото.</p>
            <p>Количество абзацев и прикрепленных к ним фото не ограничено.</p>
            <p>Каждое фото можно дополнить заголовком и описанием.</p>
            <p>При удалении абзаца все прикрепленные к нему фото удаляются.</p>";

        return $this->render('admin/shared/show_category.html.twig', [
            'category' => $work,
            'help' => $help
        ]);
    }

    /**
     * @Route("/админ/портфолио/{slug}/удалить", name="admin_delete_work")
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

        $this->addFlash('notice', "\"{$work->getTitle()}\" удалена из портфолио");

        return $this->redirectToRoute('admin_works');
    }
}
