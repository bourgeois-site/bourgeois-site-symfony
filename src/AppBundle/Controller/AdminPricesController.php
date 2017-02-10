<?php
namespace AppBundle\Controller;

use AppBundle\Form\Type\CategoryType;
use AppBundle\Entity\Category;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class AdminPricesController extends Controller
{
    /**
     * @Route("/админ/цены", name="admin_prices")
     */
    public function indexAction()
    {
        $prices = $this->getDoctrine()->
            getRepository('AppBundle:Category')->
            findByType('price');

        $help = "<p>Здесь можно создать/редактировать/удалить прайс-листы.</p>";

        return $this->render('admin/prices/index.html.twig', [
            'prices' => $prices,
            'help' => $help
        ]);
    }

    /**
     * @Route("/админ/цены/создать", name="admin_new_price")
     */
    public function newAction(Request $request)
    {
        $price = new Category();

        $form = $this->createForm(CategoryType::class, $price);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $price = $form->getData();
            $price->setType('price');
            $price->generateSlug();
            $em = $this->getDoctrine()->getManager();
            $em->persist($price);
            $em->flush();

            $this->addFlash('notice', "Добавлен новый прайс");

            return $this->redirectToRoute('admin_prices');
        }

        $help = "<p>Не забывайте прикреплять файл</p>";

        return $this->render('admin/prices/new.html.twig', [
            'form' => $form->createView(),
            'help' => $help
        ]);
    }

    /**
     * @Route("/админ/цены/{slug}/редактировать", name="admin_edit_price")
     */
    public function editAction($slug, Request $request)
    {
        $price = $this->getDoctrine()->
            getRepository('AppBundle:Category')->
            findOneBy(array('type' => 'price', 'slug' => $slug));

        if (!$price) {
            throw $this->createNotFoundException();
        }

        $form = $this->createForm(CategoryType::class, $price);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $price = $form->getData();
            $price->generateSlug();

            $em = $this->getDoctrine()->getManager();
            $em->persist($price);
            $em->flush();

            $this->addFlash('notice', "Изменения в \"{$price->getTitle()}\" произведены");

            return $this->redirectToRoute('admin_prices');
        }

        $help = "<p>Не забывайте прикреплять файл</p>";

        return $this->render('admin/prices/edit.html.twig', [
            'form' => $form->createView(),
            'help' => $help
        ]);
    }

    /**
     * @Route("/админ/цены/{slug}/удалить", name="admin_delete_price")
     */
    public function deleteAction($slug, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $price = $em->getRepository('AppBundle:Category')->
            findOneBy(array('type' => 'price', 'slug' => $slug));

        if (!$price) {
            throw $this->createNotFoundException();
        }

        $em->remove($price);
        $em->flush();

        $this->addFlash('notice', "Прайс удален");
        return $this->redirectToRoute('admin_prices');
    }
}
