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
        $internetContact = new InternetContact();
        $realContact = new RealContact();

        $internetContactForm = $this->createFormBuilder($internetContact)->
            add('title', TextType::class, array(
                'label' => "Заголовок(будет появляться при наведении пользователем на значок)", 'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => "Вконтакте")
            ))->add('href', TextType::class, array(
                'label' => "Ссылка(адрес или email)", 'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => "https://vk.com/remont_dzer")
            ))->add('isEmail', ChoiceType::class, array(
                'label' => "Email?(Нет, если контакт - социальная сеть)", 'choices' => array(
                    "Нет" => false,
                    "Да" => true
                ), 'attr' => array('class' => 'form-control')
            ))->add('socialName', ChoiceType::class, array(
                'label' => "Провайдер(выбор нужного значка)", 'choices' => array(
                    "Нет" => null,
                    "ВКонтакте" => 'vk',
                    "Одноклассники" => 'odnoklassniki',
                    "Facebook" => 'facebook',
                    "Instagram" => 'instagram',
                    "YouTube" => 'youtube',
                    "Telegram" => 'telegram',
                    "Twitter" => 'twitter',
                    "Skype" => 'skype'
                ), 'attr' => array('class' => 'form-control')
            ))->add('submit', SubmitType::class, array(
                'label' => "Создать", 'attr' => array(
                    'class' => 'btn btn-primary btn-lg')
            ))->getForm();

        $realContactForm = $this->createFormBuilder($realContact)->
            add('title', TextType::class, array(
                'label' => "Заголовок", 'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => "г. Дзержинск")
            ))->add('address', TextType::class, array(
                'label' => "Адрес", 'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => "г. Дзержинск, ул. Грибоедова 22/11")
            ))->add('phoneNumbers', TextType::class, array(
                'label' => "Телефонные номера", 'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => '+7 (8313) 23-46-46, +7 (904) 782-65-46')
            ))->add('workTime', TextType::class, array(
                'label' => "Часы работы", 'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => "Пн-Пт: 9:00-19:00, Сб-Вс: 10:00-16:00")
            ))->add('latitude', NumberType::class, array(
                'label' => "Широта(latitude) - для показа точки на карте", 'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => '56.237328')
            ))->add('longitude', NumberType::class, array(
                'label' => "Долгота(longitude) - для показа точки на карте", 'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => '43.448866')
            ))->add('submit', SubmitType::class, array(
                'label' => "Создать", 'attr' => array(
                    'class' => 'btn btn-primary btn-lg')
            ))->getForm();

        $internetContactForm->handleRequest($request);
        $realContactForm->handleRequest($request);

        if ($internetContactForm->isSubmitted() && $internetContactForm->isValid()) {
            $contact = $internetContactForm->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($contact);
            $em->flush();

            $this->addFlash('notice', "Контакт создан");
            return $this->redirectToRoute('admin_contacts');
        }

        if ($realContactForm->isSubmitted() && $realContactForm->isValid()) {
            $contact = $realContactForm->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($contact);
            $em->flush();

            $this->addFlash('notice', "Контакт создан");
            return $this->redirectToRoute('admin_contacts');
        }

        return $this->render('admin/contacts/new.html.twig', [
            'internetContactForm' => $internetContactForm->createView(),
            'realContactForm' => $realContactForm->createView()
        ]);
    }

    /**
     * @Route("/админ/контакты/интернет/редактировать/{id}", name="admin_edit_internet_contact")
     */
    public function editInternetContactAction($id)
    {
        $contact = $this->getDoctrine()->
            getRepository('AppBundle:InternetContact')->
            findOneById($id);

        return $this->render('admin/contacts/edit_internet.html.twig', [
            'contact' => $contact
        ]);
    }

    /**
     * @Route("/админ/контакты/адреса/редактировать/{id}", name="admin_edit_real_contact")
     */
    public function editRealContactAction($id)
    {
        $contact = $this->getDoctrine()->
            getRepository('AppBundle:RealContact')->
            findOneById($id);

        return $this->render('admin/contacts/edit_real.html.twig', [
            'contact' => $contact
        ]);
    }
}

