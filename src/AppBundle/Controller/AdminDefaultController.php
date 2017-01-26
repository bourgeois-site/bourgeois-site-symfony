<?php
namespace AppBundle\Controller;

use AppBundle\Form\Type\CategoryType;
use AppBundle\Form\Type\SectionType;
use AppBundle\Form\Type\PhotoType;
use AppBundle\Entity\Category;
use AppBundle\Entity\Section;
use AppBundle\Entity\Photo;
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
        $about = $em->getRepository('AppBundle:Category')->
            findOneByType('about');

        if (!$about) {
            $about = new Category();
            $about->setTitle("О компании");
            $about->setType('about');
            $about->setSlug('о-компании');
            $em->persist($about);
            $em->flush();
        }

        return $this->render('admin/shared/show_category.html.twig', [
            'category' => $about,
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

            $this->addFlash('notice', "Изменения в \"{$title}\" произведены");

            switch($type) {
            case 'about':
                return $this->redirectToRoute('admin_about');
                break;
            case 'service':
                return $this->redirectToRoute('admin_show_service', ['slug' => $category->getSlug()]);
                break;
            case 'work':
                return $this->redirectToRoute('admin_show_work', ['slug' => $category->getSlug()]);
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
     * @Route("/админ/{slug}/абзац/новый", name="admin_new_section")
     */
    public function newSectionAction($slug)
    {
        $section = new Section();

        $em = $this->getDoctrine()->getManager();
        $category = $em->getRepository('AppBundle:Category')->findOneBySlug($slug);

        if (!$category) {
            throw $this->createNotFoundException();
        }

        $section->setCategory($category);
        $em->persist($section);
        $em->flush();
        
        $form = $this->createForm(SectionType::class, $section, array(
            'action' => $this->generateUrl('admin_edit_section', ['id' => $section->getId()])
        ));

        return $this->render('admin/partials/section_form.html.twig', [
            'form' => $form->createView(),
            'section' => $section
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
            if ($title == "") {
                $this->addFlash('notice', "Изменения в \"{$category->getTitle()}\" произведены");
            } else {
                $this->addFlash('notice', "Изменения в \"{$category->getTitle()} > {$title}\" произведены");
            }

            switch($type) {
            case 'about':
                return $this->redirectToRoute('admin_about');
                break;
            case 'service':
                return $this->redirectToRoute('admin_show_service', ['slug' => $category->getSlug()]);
                break;
            case 'work':
                return $this->redirectToRoute('admin_show_work', ['slug' => $category->getSlug()]);
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

    /**
     * @Route("/админ/абзац/{id}", name="admin_delete_section")
     */
    public function deleteSectionAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $section = $em->getRepository('AppBundle:Section')->find($id);

        if (!$section) {
            throw $this->createNotFoundException();
        }

        $title = $section->getTitle();
        $category = $section->getCategory();
        $slug = $category->getSlug();
        $type = $category->getType();

        foreach ($section->getPhotos() as $photo) {
            $em->remove($photo);
        }
        $em->flush();

        $em->remove($section);
        $em->flush();

        return $this->render('partials/flash_messages.html.twig', [
            'flashBag' => ["Абзац \"{$title}\" удален"]
        ]);
    }

    /**
     * @Route("/админ/{sectionId}/фото/новое", name="admin_new_photo")
     */
    public function newPhotoAction($sectionId)
    {
        $photo = new Photo();

        $em = $this->getDoctrine()->getManager();
        $section = $em->getRepository('AppBundle:Section')->find($sectionId);

        if (!$section) {
            throw $this->createNotFoundException();
        }

        $photo->setSection($section);
        $em->persist($photo);
        $em->flush();

        $form = $this->createForm(PhotoType::class, $photo, array(
            'action' => $this->generateUrl('admin_edit_photo', ['id' => $photo->getId()])
        ));

        return $this->render('admin/partials/photo_form.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/админ/фото/{id}/редактировать", name="admin_edit_photo")
     */
    public function editPhotoAction($id, Request $request)
    {
        $photo = $this->getDoctrine()->
            getRepository('AppBundle:Photo')->find($id);

        if (!$photo) {
            throw $this->createNotFoundException();
        }

        $category = $photo->getSection()->getCategory();
        $type = $category->getType();
        $slug = $category->getSlug();

        $form = $this->createForm(PhotoType::class, $photo, array(
            'action' => $this->generateUrl('admin_edit_photo', ['id' => $id])
        ));

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $photo = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($photo);
            $em->flush();

            switch($type) {
            case 'about':
                return $this->redirectToRoute('admin_about');
                break;
            case 'service':
                return $this->redirectToRoute('admin_show_service', ['slug' => $category->getSlug()]);
                break;
            case 'work':
                return $this->redirectToRoute('admin_show_work', ['slug' => $category->getSlug()]);
                break;
            default:
                return $this->redirectToRoute('admin_homepage');
            }
        }

        return $this->render('admin/partials/photo_form.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/админ/фото/{id}/удалить", name="admin_delete_photo")
     */
    public function deletePhotoAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $photo = $em->getRepository('AppBundle:Photo')->find($id);

        if (!$photo) {
            throw $this->createNotFoundException();
        }

        $category = $photo->getSection()->getCategory();
        $type = $category->getType();
        $slug = $category->getSlug();

        $em->remove($photo);
        $em->flush();

        $this->addFlash('notice', "Фото удалено");

        switch($type) {
        case 'about':
            return $this->redirectToRoute('admin_about');
            break;
        case 'service':
            return $this->redirectToRoute('admin_show_service', ['slug' => $slug]);
            break;
        case 'work':
            return $this->redirectToRoute('admin_show_work', ['slug' => $slug]);
            break;
        default:
            return $this->redirectToRoute('admin_homepage');
        }
    }
}
