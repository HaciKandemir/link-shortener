<?php

namespace App\Controller\Panel\Admin;

use App\Entity\Url;
use App\Entity\UrlStats;
use App\Entity\User;
use App\Form\UrlFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class UrlController extends AbstractController
{
    /**
     * @Route("/admin", name="admin_index")
     * @Route("/admin/url", name="admin_url")
     * @Route("/admin/url/panel", name="url_panel")
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

    /**
     * @Route("/admin/url/{id}/edit", name="url_edit")
     */
    public function edit(Request $request, int $id): Response
    {
        $em = $this->getDoctrine()->getManager();

        $url = $em->getRepository(Url::class)->find($id);
        if (!$url){
            $ref = $request->headers->get('referer');
            return $this->redirect(parse_url($ref)['path']);
        }
        $form = $this->createForm(UrlFormType::class, $url);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $url = $form->getData();

            $url->setUpdateAt( (new \DateTime()) );
            $em->persist($url);
            $em->flush();
            $this->addFlash(
                'success',
                'Your changes were saved!'
            );
        }

        return $this->render('panel/admin/url/url_form.html.twig', [
            "form"  => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/url/{id}/delete", name="url_delete")
     */
    public function delete(int $id)
    {
        $em = $this->getDoctrine()->getManager();

        $url = $em->getRepository(Url::class)->find($id);
        $em->remove($url);
        $em->flush();
        $this->addFlash(
            'success',
            'Url deleted!'
        );

        return $this->redirectToRoute('url_panel');
    }

    /**
     * @Route("/admin/url/create", name="admin_url_create")
     */
    public function create(Request $request, UserInterface $user)
    {
        $form = $this->createForm(UrlFormType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $alpha_numeric = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
            $url_hash = substr( str_shuffle($alpha_numeric),0,5);

            $url = new Url();
            $url = $form->getData();
            $url->setUrlHash( $url_hash )
                ->setCreatedAt( (new \DateTime()) )
                ->setClickCount(0)
                ->setExpiredAt(( new \DateTime() ));

            $em = $this->getDoctrine()->getManager();
            $em->persist($url);
            $em->flush();

            $this->addFlash(
                'success',
                'Your url created!'
            );

            return $this->redirectToRoute('url_panel');
        }
        return $this->render('panel/admin/url/url_form.html.twig', [
            "form"  => $form->createView(),
        ]);
    }
}
