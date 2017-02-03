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
        $contactWithMainPhone = $this->getDoctrine()->
            getRepository('AppBundle:RealContact')->
            findOneByIsMainPhone(true);

        if (!$contactWithMainPhone) {
            $mainPhoneAbsent = true;
        } else {
            $mainPhoneAbsent = false;
        }

        $help = "<p>Здесь можно создать/изменить/удалить контакты, а также выбрать главный номер для звонка со смартфона</p>
            <p>Главный номер выбирается из числа номеров, указанных как \"Главный номер\" при создании/изменении адресов</p>";

        return $this->render('admin/contacts/index.html.twig', [
            'internetContacts' => $internetContacts,
            'realContacts' => $realContacts,
            'internetCount' => $internetCount,
            'realCount' => $realCount,
            'mainPhoneAbsent' => $mainPhoneAbsent,
            'help' => $help
        ]);
    }

    /**
     * @Route("/админ/контакты/новый", name="admin_new_contact")
     */
    public function newContactAction(Request $request)
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

        $help = "<h4>Интернет контакт</h4><p>Контакты 2х типов</p>
            <ul><li>электронная почта</li><li>соц. сети и мессенджеры</li></ul>
            <p>Для каждого контакта нужно выбрать заголовок, который будет видеть пользователь, и адрес ресурса.</p>
            <p>Для соц. сетей есть варианты значков</p>
            <h4>Адрес</h4><p>Телефонный номер в поле \"Главный номер\" можно будет выбрать в дальнейшем для звонка со смартфона</p>
            <p>Особо нужно обратить внимание на данные в поле \"широта/долгота\", так как от этого зависит корректность отображения на карте.</p>
            <p>Формат такой: XX.XXXXXX</p>
            <hr>
            <p>После создания контактов не забывайте проверять результат на сайте(в разделе \"Контакты\" и внизу страницы).</p>";

        return $this->render('admin/contacts/new.html.twig', [
            'internetContactForm' => $internetContactForm->createView(),
            'realContactForm' => $realContactForm->createView(),
            'help' => $help
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

        $help = "<p>Контакты 2х типов</p>
            <ul><li>электронная почта</li><li>соц. сети и мессенджеры</li></ul>
            <p>Для каждого контакта нужно выбрать заголовок, который будет видеть пользователь, и адрес ресурса.</p>
            <p>Для соц. сетей есть варианты значков</p>
            <hr>
            <p>После редактирования контактов не забывайте проверять результат на сайте(в разделе \"Контакты\" и внизу страницы).</p>";

        return $this->render('admin/contacts/edit.html.twig', [
            'title' => $title,
            'form' => $form->createView(),
            'help' => $help
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

        $help = "<p>Телефонный номер в поле \"Главный номер\" можно будет выбрать в дальнейшем для звонка со смартфона</p>
            <p>Особо нужно обратить внимание на данные в поле \"широта/долгота\", так как от этого зависит корректность отображения на карте.</p>
            <p>Формат такой: XX.XXXXXX</p>
            <hr>
            <p>После редактирования контактов не забывайте проверять результат на сайте(в разделе \"Контакты\" и внизу страницы).</p>";

        return $this->render('admin/contacts/edit.html.twig', [
            'title' => $title,
            'form' => $form->createView(),
            'help' => $help
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

