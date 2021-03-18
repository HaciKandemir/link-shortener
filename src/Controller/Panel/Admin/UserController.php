<?php

namespace App\Controller\Panel\Admin;

use App\Entity\User;
use App\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserController extends AbstractController
{
    /**
     * @Route("/panel/admin/user", name="panel_admin_user")
     */
    public function index(): Response
    {
        $user_repository = $this->getDoctrine()->getRepository(User::class);
        $users = $user_repository->findAll();
        return $this->render('panel/admin/user/index.html.twig', [
            'all_users' => $users,
        ]);
    }
    /**
     * @Route("/panel/admin/user/{id}", name="panel_profile_settings")
     */
    public function edit(int $id, Request $request, UserPasswordEncoderInterface $passwordEncoder):Response
    {
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository(User::class)->find($id);

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
        return $this->render('panel/admin/user/user_edit.html.twig', [
            "form"  => $form->createView(),
            "roles" => $user->getRoles(),
        ]);
    }
}
