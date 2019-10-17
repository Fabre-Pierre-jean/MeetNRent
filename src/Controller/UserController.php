<?php

namespace App\Controller;

use App\Entity\User;
use http\Env\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    /**
     * Permet d'afficher le profil de l'user connecté
     *
     * @Route("/user/my_profile", name="my_profile")
     *
     * @IsGranted("ROLE_USER")
     *
     */
    public function myProfile(){
        return $this->render('user/index.html.twig', [
            'user' => $this->getUser()
        ]);
    }

    /**
     * @Route("/user/{slug}", name="user_profile")
     *
     * @IsGranted("ROLE_USER")
     */
    public function index(User $user)
    {
        return $this->render('user/index.html.twig', [
            'user' => $user,
        ]);
    }
}
