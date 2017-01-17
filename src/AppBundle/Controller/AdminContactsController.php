<?php
namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

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

