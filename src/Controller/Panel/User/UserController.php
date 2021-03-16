<?php

namespace App\Controller\Panel\User;

use App\Entity\User;
use App\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserController extends AbstractController
{
    /**
     * @Route("/profile/settings", name="profile_settings")
     */
    public function index(Request $request, UserInterface $user, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $em = $this->getDoctrine()->getManager();

        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();
            if ($form->get('plainPassword')->getData() !== null){
                $user->setPassword(
                    $passwordEncoder->encodePassword(
                        $user,
                        $form->get('plainPassword')->getData()
                    )
                );
            }
            $em->persist($user);
            $em->flush();

            $this->addFlash(
                'success',
                'Your changes were saved!'
            );
        }
        return $this->render('panel/user/profile/index.html.twig', [
            "form"  => $form->createView(),
            "roles" => $user->getRoles(),
        ]);
    }
}
