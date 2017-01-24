<?php
namespace AppBundle\Controller;

use AppBundle\Form\Type\CategoryType;
use AppBundle\Form\Type\SectionType;
use AppBundle\Entity\Category;
use AppBundle\Entity\Section;
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

        $title = $category->getTitle();

        $form = $this->createForm(CategoryType::class, $category, array(
            'action' => $this->generateUrl('admin_edit_category', [
                'type' => $type, 'slug' => $slug])
        ));

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $category = $form->getData();

            if ($category->getType() != 'about') {
                $category->generateSlug();
            }

            $em = $this->getDoctrine()->getManager();
            $em->persist($category);
            $em->flush();

            $this->addFlash('notice', "Изменения в '{$title}' произведены");

            switch($type) {
            case 'about':
                return $this->redirectToRoute('admin_about');
                break;
            case 'service':
                return $this->redirectToRoute('admin_services');
                break;
            case 'work':
                return $this->redirectToRoute('admin_works');
                break;
            default:
                return $this->redirectToRoute('admin_homepage');
            }
        }

        return $this->render('admin/partials/category_form.html.twig', [
            'category' => $category,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/админ/{categoryId}/абзац/новый", name="admin_new_section")
     */
    public function newSectionAction($categoryId, Request $request)
    {
        $section = new Section();

        $em = $this->getDoctrine()->getManager();
        $category = $em->getRepository('AppBundle:Category')->find($categoryId);

        if (!$category) {
            throw $this->createNotFoundException();
        }

        $form = $this->createForm(SectionType::class, $section, array(
            'action' => $this->generateUrl('admin_new_section', ['categoryId' => $categoryId])
        ));

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $section = $form->getData();
            $section->setCategory($category);
            $em->persist($section);
            $em->flush();

            $this->addFlash('notice', "'{$section->getTitle()}' добавлен в '{$category->getTitle()}'");

            return $this->redirect($request->server->get('HTTP_REFERER'));
        }

        return $this->render('admin/partials/section_form.html.twig', [
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

        $title = $section->getTitle();

        $form = $this->createForm(SectionType::class, $section, array(
            'action' => $this->generateUrl('admin_edit_section', ['id' => $id])
        ));

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $section = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($section);
            $em->flush();

            $category = $section->getCategory();
            $type = $category->getType();

            $this->addFlash('notice', "Изменения в '{$category->getTitle()} > {$title}' произведены");

            switch($type) {
            case 'about':
                return $this->redirectToRoute('admin_about');
                break;
            case 'service':
                return $this->redirectToRoute('admin_services');
                break;
            case 'work':
                return $this->redirectToRoute('admin_works');
                break;
            default:
                return $this->redirectToRoute('admin_homepage');
            }
        }

        return $this->render('admin/partials/section_form.html.twig', [
            'section' => $section,
            'form' => $form->createView()
        ]);
    }
}
