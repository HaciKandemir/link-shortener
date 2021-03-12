<?php

namespace App\Controller;

use App\Entity\Url;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UrlController extends AbstractController
{

    /**
     * @Route("/url/create", name="url_create")
     */
    public function create(Request $request): JsonResponse
    {
        $url = $request->get('url');

        # url validation

        # generate  digit hash
        $alpha_numeric = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
        $url_hash = substr( str_shuffle($alpha_numeric),0,5);

        $em = $this->getDoctrine()->getManager();

        $url_item = new Url();
        $url_item->setUrl($url)
            ->setUrlHash( rand(0,9).rand(0,9).rand(0,9).rand(0,9) )
            ->setCreatedAt( (new \DateTime()) )
            ->setUserId(1)
            ->setClickCount(0)
            ->setIsPublic(true)
            ->setExpiredAt( (new \DateTime()) )
            ->setIsActive(true);

        $em->persist($url_item);
        $em->flush();

        return new JsonResponse([
            'response'=>true,
            'error'=>false,
            'errorMessage'=>null
        ],200);
    }

    /**
     * @Route("/{urlHash}", name="redirector")
     */
    public function redirector($urlHash): Response
    {
        $em = $this->getDoctrine()->getManager();

        $urlRepository = $em->getRepository( Url::class);

        $url_item = $urlRepository->findOneBy([
            'is_active'=>true,
            'urlHash'=>$urlHash
        ]);

        $url = $url_item->getUrl();

        # TODO : create stats
        //$this->saveStats();

        return $this->redirect($url);
    }
}
