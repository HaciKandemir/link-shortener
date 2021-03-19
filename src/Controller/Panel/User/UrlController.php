<?php

namespace App\Controller\Panel\User;

use App\Entity\Url;
use App\Entity\UrlStats;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class UrlController extends AbstractController
{
    /**
     * @Route("/panel/user/url", name="user_url_stats")
     * @Route("/panel/user/url/{id}", name="panel_user_url_stats")
     */
    public function index(UserInterface $user, int $id=null): Response
    {
        if ($id){
            $user = $this->getDoctrine()->getRepository(User::class)->find($id);
        }
        $url_repository = $this->getDoctrine()->getRepository(Url::class);
        $url_stats_repository = $this->getDoctrine()->getRepository(UrlStats::class);

        $your_urls = $url_repository->findBy(['user'=>$user]);
        $top_clicks = $url_repository->findBy(['user'=>$user],['click_count'=>'DESC'],5);

        $top_browsers = $url_stats_repository->topBrowser($your_urls);
        $top_devices = $url_stats_repository->topDevice($your_urls);

        return $this->render('panel/user/url/index.html.twig', [
            'all_urls'     => $your_urls,
            'top_clicks'   => $top_clicks,
            'top_browsers' => $top_browsers,
            'top_devices'  => $top_devices,
            'user_name'    => $user->getUsername(),
        ]);
    }
}
