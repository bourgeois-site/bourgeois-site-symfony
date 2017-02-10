<?php
namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class PricesController extends Controller
{
    /**
     * @Route("/цены", name="prices")
     */
    public function indexAction()
    {
        $prices = $this->getDoctrine()->getRepository('AppBundle:Category')->
            findByType('price');

        if (!$prices) {
            throw $this->createNotFoundException();
        }

        return $this->render('prices/index.html.twig',[
            'prices' => $prices
        ]);
    }
}
