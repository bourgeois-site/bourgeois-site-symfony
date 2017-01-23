<?php
namespace AppBundle\Controller;

use AppBundle\Form\Type\CategoryType;
use AppBundle\Form\Type\SectionType;
use AppBundle\Entity\Category;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class AdminDefaultController extends Controller
{
    /**
     * @Route("/админ", name="admin_homepage")
     */
    public function indexAction()
    {
        return $this->redirectToRoute('admin_new_requests');
    }

    /**
     * @Route("/админ/о-компании", name="admin_about")
     */
    public function aboutAction()
    {
        $em = $this->getDoctrine()->getManager();
        $category = $em->getRepository('AppBundle:Category')->
            findOneByType('about');

        if (!$category) {
            $category = new Category();
            $category->setTitle("О компании");
            $category->setType('about');
            $category->setSlug('о-компании');
            $em->persist($category);
            $em->flush();
        }

        return $this->render('admin/shared/show_category.html.twig', [
            'category' => $category,
        ]);
    }

    /**
     * @Route("/админ/профиль", name="admin_profile")
     */
    public function profileAction()
    {
        return $this->render('admin/default/profile.html.twig');
    }

    public function asideAction()
    {
        $newRequestsCount = sizeof(
            $this->getDoctrine()->
            getRepository('AppBundle:Request')->
            findByIsArchived(false)
        );

        return $this->render('admin/partials/aside.html.twig', [
            'newRequestsCount' => $newRequestsCount
        ]);
    }

    /**
     * @Route("/админ/категория/{type}/{slug}/редактировать", name="admin_edit_category")
     */
    public function editCategoryAction($type, $slug, Request $request)
    {
        $category = $this->getDoctrine()->
            getRepository('AppBundle:Category')->
            findOneBy(array('type' => $type, 'slug' => $slug));

        $form = $this->createForm(CategoryType::class, $category);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $category = $form->getData();
            $category->generateSlug();
            $em = $this->getDoctrine()->getManager();
            $em->persist($category);
            $em->flush();
            // add flash 
            // ajax response
            return $this->redirectToRoute('admin_about'); //temporary
        }

        return $this->render('admin/partials/category_form.html.twig', [
            'category' => $category,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/админ/абзац/{id}/редактировать", name="admin_edit_section")
     */
    public function editSectionAction($id, Request $request)
    {
        $section = $this->getDoctrine()->
            getRepository('AppBundle:Section')->
            find($id);

        $form = $this->createForm(SectionType::class, $section);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $section = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($section);
            $em->flush();
            // add flash 
            // ajax response
            return $this->redirectToRoute('admin_about'); //temporary
        }

        return $this->render('admin/partials/section_form.html.twig', [
            'section' => $section,
            'form' => $form->createView()
        ]);
    }
}
