<?php

namespace App\Controller\Panel\Admin;

use App\Entity\User;
use App\Form\UserAdminType;
use App\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserController extends AbstractController
{
    /**
     * @Route("/admin/user", name="panel_admin_user")
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
     * @Route("/admin/user/create", name="panel_user_create")
     */
    public function create(Request $request, UserPasswordEncoderInterface $passwordEncoder){
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->add('is_active', CheckboxType::class, [
            'label_attr' => ['class' => 'switch-custom'],
            'required'   => false,
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );
            $user->setRoles(['ROLE_USER','ROLE_ADMIN']);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash(
                'success',
                'Admin User Created!'
            );
            return $this->redirectToRoute('panel_admin_user');
        }
        return $this->render('panel/admin/user/user_edit.html.twig', [
            "form"  => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/user/{id}", name="panel_profile_settings")
     */
    public function edit(int $id, Request $request, UserPasswordEncoderInterface $passwordEncoder):Response
    {
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository(User::class)->find($id);

        $form = $this->createForm(UserType::class, $user);
        $form->add('is_active', CheckboxType::class, [
            'label_attr' => ['class' => 'switch-custom'],
            'required'   => false,
        ]);

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

    /**
     * @Route("/admin/user/{id}/delete", name="panel_user_delete")
     */
    public function delete(int $id)
    {
        $em = $this->getDoctrine()->getManager();

        $url = $em->getRepository(User::class)->find($id);
        $em->remove($url);
        $em->flush();
        $this->addFlash(
            'success',
            'User deleted!'
        );

        return $this->redirectToRoute('panel_admin_user');
    }
}
