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
        return $this->render('prices/index.html.twig');
    }
}
