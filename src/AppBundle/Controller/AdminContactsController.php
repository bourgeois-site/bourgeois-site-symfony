<?php
namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
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
        $internetContactForm = $this->getInternetContactForm(new InternetContact());
        $realContactForm = $this->getRealContactForm(new RealContact());

        foreach ([$internetContactForm, $realContactForm] as $form) {
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $contact = $form->getData();
                $em = $this->getDoctrine()->getManager();
                $em->persist($contact);
                $em->flush();

                $this->addFlash('notice', "'{$contact->getTitle()}' добавлен в список контактов");
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
        $form = $this->getInternetContactForm($contact);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $contact = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($contact);
            $em->flush();

            $this->addFlash('notice', "'{$title}' обновлен");

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
        $form = $this->getRealContactForm($contact);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $contact = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($contact);
            $em->flush();

            $this->addFlash('notice', "'{$title}' обновлен");

            return $this->redirectToRoute('admin_contacts');
        }

        return $this->render('admin/contacts/edit.html.twig', [
            'title' => $title,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/админ/контакты/интернет/{id}", name="admin_delete_internet_contact")
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

        $this->addFlash('notice', "'{$title}' удален из контактов");

        return $this->redirectToRoute('admin_contacts');
    }

    /**
     * @Route("/админ/контакты/адреса/{id}", name="admin_delete_real_contact")
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

        $this->addFlash('notice', "'{$title}' удален из контактов");

        return $this->redirectToRoute('admin_contacts');
    }

    private function getInternetContactForm($contact)
    {
        $form = $this->createFormBuilder($contact)
            ->add('title', TextType::class, array(
                'label' => "Заголовок (будет выскакивать при наведении пользователем на контакт)", 'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => "Вконтакте")))
            ->add('href', TextType::class, array(
                'label' => "Ссылка (url или email)", 'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => "https://vk.com/remont_dzer")))
            ->add('isEmail', ChoiceType::class, array(
                'label' => "Email? (Нет, если контакт - социальная сеть)", 'choices' => array(
                    "Нет" => false,
                    "Да" => true
                ), 'attr' => array('class' => 'form-control')))
            ->add('socialName', ChoiceType::class, array(
                'label' => "Соц. сеть - выбор нужного значка (Нет, если контакт - email)", 'choices' => array(
                    "Нет" => null,
                    "ВКонтакте" => 'vk',
                    "Одноклассники" => 'odnoklassniki',
                    "Facebook" => 'facebook',
                    "Instagram" => 'instagram',
                    "YouTube" => 'youtube',
                    "Telegram" => 'telegram',
                    "Twitter" => 'twitter',
                    "Skype" => 'skype'
                ), 'attr' => array('class' => 'form-control')))
            ->add('submit', SubmitType::class, array(
                'label' => "Подтвердить", 'attr' => array(
                    'class' => 'btn btn-primary btn-lg')));

        return $form->getForm();
    }

    private function getRealContactForm($contact)
    {
        $form = $this->createFormBuilder($contact)
            ->add('title', TextType::class, array(
                'label' => "Заголовок", 'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => "г. Дзержинск")))
            ->add('address', TextType::class, array(
                'label' => "Адрес", 'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => "г. Дзержинск, ул. Грибоедова 22/11")))
            ->add('phoneNumbers', TextType::class, array(
                'label' => "Телефонные номера", 'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => '+7 (8313) 23-46-46, +7 (904) 782-65-46')))
            ->add('workTime', TextType::class, array(
                'label' => "Часы работы", 'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => "Пн-Пт: 9:00-19:00, Сб-Вс: 10:00-16:00")))
            ->add('latitude', NumberType::class, array(
                'label' => "Широта(latitude) - для показа точки на карте", 'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => '56.237328')))
            ->add('longitude', NumberType::class, array(
                'label' => "Долгота(longitude) - для показа точки на карте", 'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => '43.448866')))
            ->add('submit', SubmitType::class, array(
                'label' => "Подтвердить", 'attr' => array(
                    'class' => 'btn btn-primary btn-lg')));

        return $form->getForm();
    }
}

