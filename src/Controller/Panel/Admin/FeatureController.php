<?php

namespace App\Controller\Panel\Admin;

use App\Entity\Features;
use App\Form\FeatureType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FeatureController extends AbstractController
{
    /**
     * @Route("/admin/features", name="admin_features")
     */
    public function features(): Response
    {
        $em = $this->getDoctrine()->getManager();
        $features = $em->getRepository(Features::class)->findAll();
        return $this->render('panel/admin/feature/index.html.twig',[
            "features" => $features,
        ]);
    }

    /**
     * @Route("/admin/feature/update/{id}", name="admin_feature_update")
     * @Route("/admin/feature/create", name="admin_feature_create")
     */
    public function create(Request $request, int $id=null): Response
    {
        $em = $this->getDoctrine()->getManager();

        if (!is_null($id)){
            $feature = $em->getRepository(Features::class)->find($id);
        }else{
            $feature = new Features();
        }

        $form = $this->createForm(FeatureType::class, $feature);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $banner = $form->getData();

            $em->persist($banner);
            $em->flush();

            $this->addFlash(
                'success',
                'New feature added!'
            );
            return $this->features();
        }
        return $this->render('panel/admin/feature/feature.html.twig',[
            "form" => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/feature/delete/{id}", name="admin_feature_delete")
     */
    public function delete(int $id)
    {
        $em = $this->getDoctrine()->getManager();
        $feature = $em->getRepository(Features::class)->find($id);
        $em->remove($feature);
        $em->flush();
        $this->addFlash(
            'success',
            'feature deleted!'
        );
        return $this->features();
    }
}
