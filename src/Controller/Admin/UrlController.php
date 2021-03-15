<?php

namespace App\Controller\Admin;

use App\Entity\Url;
use App\Entity\UrlStats;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UrlController extends AbstractController
{
    /**
     * @Route("/admin", name="admin_index")
     * @Route("/admin/url", name="admin_url")
     */
    public function index(): Response
    {
        $repository = $this->getDoctrine();

        $urls = $repository->getRepository(Url::class);
        $users = $repository->getRepository(User::class);
        $urls_stats = $repository->getRepository(UrlStats::class);

        return $this->render('panel/admin/url/index.html.twig', [
            'urls' => $urls->findAll(),
            'users' => $users->findAll(),
            'user_count' =>  $users->createQueryBuilder('a')->select('count(a.id)')->getQuery()->getSingleScalarResult(),
            'redirect_count' =>  $urls_stats->createQueryBuilder('a')->select('count(a.id)')->getQuery()->getSingleScalarResult(),
            'shorten_count' =>  $urls->createQueryBuilder('a')->select('count(a.id)')->getQuery()->getSingleScalarResult()
        ]);
    }
}
