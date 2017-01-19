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
     * @Route("/админ/выполненные-работы/{slug}/редактировать", name="admin_edit_work")
     */
    public function editAction($slug)
    {
        $work = $this->getDoctrine()->
            getRepository('AppBundle:Category')->
            findOneBy(array('type' => 'work', 'slug' => $slug));

        if (!$work) {
            throw $this->createNotFoundException();
        }

        return $this->render('admin/works/edit.html.twig', [
            'work' => $work
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

        $this->addFlash('notice', "{$work->getTitle()} удалены из выполненных работ");

        return $this->redirectToRoute('admin_works');
    }
}
