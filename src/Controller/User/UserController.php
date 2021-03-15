<?php

namespace App\Controller\User;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class UserController extends AbstractController
{
    /**
     * @Route("/profile/user", name="profile_user")
     */
    public function index(UserInterface $user): Response
    {
        return $this->render('panel/user/profile/index.html.twig', [
            'user' => $user,
        ]);
    }
}
