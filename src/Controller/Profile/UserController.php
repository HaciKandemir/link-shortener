<?php

namespace App\Controller\Profile;

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
        return $this->render('profile/user/index.html.twig', [
            'user' => $user,
        ]);
    }
}
