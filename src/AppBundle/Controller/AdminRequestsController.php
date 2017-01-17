<?php
namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

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

    /**
     * @Route("/админ/заявки/архивировать", name="admin_archive_request")
     */
    public function archiveRequestAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $request = $em->getRepository('AppBundle:Request')->
            find($request->query->get('id'));

        if (!$request) {
            throw $this->createNotFoundException();
        }

        $request->setIsArchived(true);
        $em->flush();

        $this->addFlash('notice', "Заявка от {$request->getName()} обработана и помещена в архив");

        return $this->redirectToRoute('admin_new_requests');
    }

    /**
     * @Route("/админ/заявки/восстановить", name="admin_restore_request")
     */
    public function restoreRequestAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $request = $em->getRepository('AppBundle:Request')->
            find($request->query->get('id'));

        if (!$request) {
            throw $this->createNotFoundException();
        }

        $request->setIsArchived(false);
        $request->setCreatedAt(new \DateTime());

        $em->flush();

        $this->addFlash('notice', "Заявка от {$request->getName()} возвращена в список новых заявок");

        return $this->redirectToRoute('admin_archived_requests');
    }
}
