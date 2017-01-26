<?php
namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use AppBundle\Entity\Request as userRequest;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('AppBundle:Category');

        $about = $repo->findOneByType('about');
        $services = $repo->findByType('service');
        $works = $repo->findByType('work');

        $callForm = $this->createFormBuilder(new userRequest())
            ->add('name', TextType::class, array(
                'label' => "Имя", 'attr' => array('class' => 'form-control')))
            ->add('email', EmailType::class, array(
                'required' => false, 'label' => 'Email',
                'attr' => array('class' => 'form-control')))
            ->add('address', TextType::class, array(
                'required' => false, 'label' => "Адрес",
                'attr' => array('class' => 'form-control')))
            ->add('phoneNumber', TextType::class, array(
                'label' => "Телефон", 'attr' => array('class' => 'form-control')))
            ->add('comment', TextareaType::class, array(
                'required' => false, 'label' => "Комментарий",
                'attr' => array('class' => 'form-control')))
            ->add('submit', SubmitType::class, array(
                'label' => "Подтвердить",
                'attr' => array('class' => 'btn btn-danger')))
            ->getForm();

        $callForm->handleRequest($request);

        if ($callForm->isSubmitted() && $callForm->isValid()) {
            $userRequest = $callForm->getData();
            $userRequest->setCreatedAt(new \DateTime());
            $userRequest->setIsArchived(false);
            $em->persist($userRequest);
            $em->flush();

            $this->addFlash('notice', "Заявка получена и будет обработана в ближайшее время");
            return $this->redirectToRoute('homepage');
        }

        return $this->render('default/index.html.twig', [
            'about' => $about,
            'services' => $services,
            'works' => $works,
            'callForm' => $callForm->createView()
        ]);
    }

    /**
     * @Route("/о-компании", name="aboutpage")
     */
    public function aboutAction()
    {
        $category = $this->getDoctrine()->
            getRepository('AppBundle:Category')->
            findOneByType('about');

        if (!$category) {
            throw $this->createNotFoundException();
        }

        $title = $category->getTitle();

        $sections = $category->getSections();

        return $this->render('default/about.html.twig',
            ['title' => $title, 'sections' => $sections]);
    }

    /**
     * @Route("/контакты", name="contacts")
     */
    public function contactsAction()
    {
        $title = "Контакты";

        $real_contacts = $this->getDoctrine()->
            getRepository('AppBundle:RealContact')->
            findAll();

        $email_contacts = $this->getDoctrine()->
            getRepository('AppBundle:InternetContact')->
            findByIsEmail(1);

        $social_contacts = $this->getDoctrine()->
            getRepository('AppBundle:InternetContact')->
            findByIsEmail(0);

        return $this->render('default/contacts.html.twig', [
            'title' => $title,
            'real_contacts' => $real_contacts,
            'email_contacts' => $email_contacts,
            'social_contacts' => $social_contacts
        ]);
    }

    public function headerAction()
    {
        $services = $this->getDoctrine()->
            getRepository('AppBundle:Category')->
            findByType('service');

        $works = $this->getDoctrine()->
            getRepository('AppBundle:Category')->
            findByType('work');

        return $this->render('partials/header.html.twig', [
            'services' => $services,
            'works' => $works
        ]);
    }

    public function footerAction()
    {
        $company = [
            "О компании" => $this->generateUrl('aboutpage'),
            "Цены" => $this->generateUrl('prices')
        ];

        $services = $this->getDoctrine()->
            getRepository('AppBundle:Category')->
            findByType('service');

        $works = $this->getDoctrine()->
            getRepository('AppBundle:Category')->
            findByType('work');

        $email_contacts = $this->getDoctrine()->
            getRepository('AppBundle:InternetContact')->
            findByIsEmail(1);

        $social_contacts = $this->getDoctrine()->
            getRepository('AppBundle:InternetContact')->
            findByIsEmail(0);

        $real_contacts = $this->getDoctrine()->
            getRepository('AppBundle:RealContact')->
            findAll();

        return $this->render('partials/footer.html.twig', [
            'company' => $company,
            'services' => $services,
            'works' => $works,
            'email_contacts' => $email_contacts,
            'social_contacts' => $social_contacts,
            'real_contacts' => $real_contacts
        ]);
    }
}
