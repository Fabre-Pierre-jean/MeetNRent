<?php

namespace App\Controller;

use App\Entity\User;
use http\Env\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    /**
     * @Route("/user/{slug}", name="user_profile")
     */
    public function index(User $user)
    {
        return $this->render('user/index.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * Permet d'afficher le profil de l'user connecté
     *
     * @Route("/my_profile", name="my_profile")
     *
     */
    public function myProfile(){
        return $this->render('user/index.html.twig', [
            'user' => $this->getUser()
        ]);
    }
}
