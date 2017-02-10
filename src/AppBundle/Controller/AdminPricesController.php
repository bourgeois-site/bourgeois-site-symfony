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
    }

    /**
     * @Route("/админ/цены/{slug}/редактировать", name="admin_edit_price")
     */
    public function editAction($slug, Request $request)
    {
    }

    /**
     * @Route("/админ/цены/{slug}/удалить", name="admin_delete_price")
     */
    public function deleteAction($slug, Request $request)
    {
    }
}
