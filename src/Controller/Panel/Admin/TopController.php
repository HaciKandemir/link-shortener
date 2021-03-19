<?php

namespace App\Controller\Panel\Admin;

use App\Entity\Url;
use App\Entity\UrlStats;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TopController extends AbstractController
{
    /**
     * @Route("/panel/admin/top", name="panel_admin_top")
     */
    public function index(): Response
    {
        $url_repository = $this->getDoctrine()->getRepository(Url::class);
        $url_stats_repository = $this->getDoctrine()->getRepository(UrlStats::class);

        $top_shorten_domain = $url_repository->topShortenDomain(5);

        $top_browsers = $url_stats_repository->topBrowser($url_stats_repository->findAll(),10);
        $top_lang = $url_stats_repository->topLanguage(10);
        $top_user = $url_repository->topUser(10);

        return $this->render('panel/admin/top/index.html.twig', [
            'top_browsers'         => $top_browsers,
            'top_shorten_domain'   => $top_shorten_domain,
            'top_lang'             => $top_lang,
            'top_user'             => $top_user,
        ]);
    }
}
