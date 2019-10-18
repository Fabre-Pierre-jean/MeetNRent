<?php

namespace App\Controller;

use App\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    /**
     * Permet d'afficher le profil de l'user connecté
     *
     * @Route("/account", name="my_profile")
     * @Route("/account/#my_ads", name="my_ads")
     * @IsGranted("ROLE_USER")
     *
     */
    public function myProfile(){
        return $this->render('user/index.html.twig', [
            'user' => $this->getUser()
        ]);
    }

    /**
     *
     * Affiche le profil d'un user
     *
     * @Route("/user/{slug}", name="user_profile")
     *
     * @IsGranted("ROLE_USER")
     */
    public function index(User $user)
    {
        $user_connected = $this->getUser();
        return $this->render('user/index.html.twig', [
            'user'           => $user,
            'user_connected' => $user_connected
        ]);
    }
}
