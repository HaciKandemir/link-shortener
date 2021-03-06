<?php

namespace App\Controller\Panel\Admin;

use App\Entity\Banner;
use App\Entity\TermsOfUse;
use App\Form\BannerType;
use App\Form\TermsOfUseType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SiteSettingController extends AbstractController
{
    /**
     * @Route("/admin/site-setting/banner", name="admin_site_setting_banner")
     */
    public function banner(Request $request): Response
    {
        $em = $this->getDoctrine()->getManager();
        $banner = $em->getRepository(Banner::class)->find(1);

        $form = $this->createForm(BannerType::class, $banner);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $banner = $form->getData();

            $em->persist($banner);
            $em->flush();

            $this->addFlash(
                'success',
                'Your changes were saved!'
            );
        }
        return $this->render('panel/admin/site_setting/banner.html.twig',[
            "form" => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/site-setting/privacy", name="admin_site_setting_privacy")
     */
    public function privacy(Request $request): Response
    {
        $em = $this->getDoctrine()->getManager();
        $privacy = $em->getRepository(TermsOfUse::class)->find(1);

        $form = $this->createForm(TermsOfUseType::class, $privacy);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $privacy = $form->getData();

            $em->persist($privacy);
            $em->flush();

            $this->addFlash(
                'success',
                'Your changes were saved!'
            );
        }
        return $this->render('panel/admin/site_setting/privacy.html.twig',[
            "form" => $form->createView(),
        ]);
    }
}
