<?php
namespace AppBundle\Controller;

use AppBundle\Form\Type\RealContactType;
use AppBundle\Form\Type\InternetContactType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\InternetContact;
use AppBundle\Entity\RealContact;

class AdminContactsController extends Controller
{
    /**
     * @Route("/админ/контакты", name="admin_contacts")
     */
    public function indexAction()
    {
        $internetContacts = $this->getDoctrine()->
            getRepository('AppBundle:InternetContact')->
            findBy([], array('isEmail' => 'desc'));
        $internetCount = sizeof($internetContacts);

        $realContacts = $this->getDoctrine()->
            getRepository('AppBundle:RealContact')->
            findAll();
        $realCount = sizeof($realContacts);

        return $this->render('admin/contacts/index.html.twig', [
            'internetContacts' => $internetContacts,
            'realContacts' => $realContacts,
            'internetCount' => $internetCount,
            'realCount' => $realCount
        ]);
    }

    /**
     * @Route("/админ/контакты/новый", name="admin_new_contact")
     */
    public function newInternetContactAction(Request $request)
    {
        $internetContactForm = $this->createForm(
            InternetContactType::class, new InternetContact());

        $realContactForm = $this->createForm(
            RealContactType::class, new RealContact());

        foreach ([$internetContactForm, $realContactForm] as $form) {
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $contact = $form->getData();
                $em = $this->getDoctrine()->getManager();
                $em->persist($contact);
                $em->flush();

                $this->addFlash('notice', "\"{$contact->getTitle()}\" добавлен в список контактов");
                return $this->redirectToRoute('admin_contacts');
            }
        }

        return $this->render('admin/contacts/new.html.twig', [
            'internetContactForm' => $internetContactForm->createView(),
            'realContactForm' => $realContactForm->createView()
        ]);
    }

    /**
     * @Route("/админ/контакты/интернет/{id}/редактировать", name="admin_edit_internet_contact")
     */
    public function editInternetContactAction($id, Request $request)
    {
        $contact = $this->getDoctrine()->
            getRepository('AppBundle:InternetContact')->
            findOneById($id);

        if (!$contact) {
            throw $this->createNotFoundException();
        }

        $title = $contact->getTitle();
        $form = $this->createForm(InternetContactType::class, $contact);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $contact = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($contact);
            $em->flush();

            $this->addFlash('notice', "\"{$title}\" обновлен");

            return $this->redirectToRoute('admin_contacts');
        }

        return $this->render('admin/contacts/edit.html.twig', [
            'title' => $title,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/админ/контакты/адреса/{id}/редактировать", name="admin_edit_real_contact")
     */
    public function editRealContactAction($id, Request $request)
    {
        $contact = $this->getDoctrine()->
            getRepository('AppBundle:RealContact')->
            findOneById($id);

        if (!$contact) {
            throw $this->createNotFoundException();
        }

        $title = $contact->getTitle();
        $form = $this->createForm(RealContactType::class, $contact);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $contact = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($contact);
            $em->flush();

            $this->addFlash('notice', "\"{$title}\" обновлен");

            return $this->redirectToRoute('admin_contacts');
        }

        return $this->render('admin/contacts/edit.html.twig', [
            'title' => $title,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/админ/контакты/интернет/{id}/удалить", name="admin_delete_internet_contact")
     */
    public function deleteInternetContactAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $contact = $em->getRepository('AppBundle:InternetContact')->find($id);
        $title = $contact->getTitle();

        if (!$contact) {
            throw $this->createNotFoundException();
        }

        $em->remove($contact);
        $em->flush();

        $this->addFlash('notice', "\"{$title}\" удален из контактов");

        return $this->redirectToRoute('admin_contacts');
    }

    /**
     * @Route("/админ/контакты/адреса/{id}/удалить", name="admin_delete_real_contact")
     */
    public function deleteRealContactAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $contact = $em->getRepository('AppBundle:RealContact')->find($id);
        $title = $contact->getTitle();

        if (!$contact) {
            throw $this->createNotFoundException();
        }

        $em->remove($contact);
        $em->flush();

        $this->addFlash('notice', "\"{$title}\" удален из контактов");

        return $this->redirectToRoute('admin_contacts');
    }
}

