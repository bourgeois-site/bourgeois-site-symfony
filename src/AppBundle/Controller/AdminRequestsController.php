<?php
namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class AdminRequestsController extends Controller
{
    /**
     * @Route("/админ/заявки/новые/{page}", name="admin_new_requests", defaults={"page": 1})
     */
    public function indexNewAction($page)
    {
        $limit = 15;
        $offset = $limit * ($page - 1);

        $repo = $this->getDoctrine()->getRepository('AppBundle:Request');

        $allRequestsCount = sizeof($repo->findByIsArchived(false));

        $pagesCount = ceil($allRequestsCount / $limit);

        $query = $repo->createQueryBuilder('r')->
            where('r.isArchived = false')->
            orderBy('r.createdAt', 'desc')->
            setFirstResult($offset)->
            setMaxResults($limit)->
            getQuery();

        $requests = $query->getResult();

        return $this->render('admin/requests/index.html.twig', [
            'type' => 'Новые',
            'page' => $page,
            'pagesCount' => $pagesCount,
            'path' => 'admin_new_requests',
            'allRequestsCount' => $allRequestsCount,
            'requests' => $requests
        ]);
    }

    /**
     * @Route("/админ/заявки/архив/{page}", name="admin_archived_requests", defaults={"page": 1})
     */
    public function indexArchivedAction($page)
    {
        $limit = 15;
        $offset = $limit * ($page - 1);

        $repo = $this->getDoctrine()->getRepository('AppBundle:Request');

        $allRequestsCount = sizeof($repo->findByIsArchived(true));

        $pagesCount = ceil($allRequestsCount / $limit);

        $query = $repo->createQueryBuilder('r')->
            where('r.isArchived = true')->
            orderBy('r.createdAt', 'desc')->
            setFirstResult($offset)->
            setMaxResults($limit)->
            getQuery();

        $requests = $query->getResult();

        return $this->render('admin/requests/index.html.twig', [
            'type' => 'Обработанные',
            'page' => $page,
            'pagesCount' => $pagesCount,
            'path' => 'admin_archived_requests',
            'allRequestsCount' => $allRequestsCount,
            'requests' => $requests
        ]);
    }
}
