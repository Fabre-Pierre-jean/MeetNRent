<?php


namespace App\Controller;


use App\Entity\Ad;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

class HomeController extends AbstractController
{

    /**
     * @Route("/", name="homepage")
     */
    public function home(){
        return $this->render('home.html.twig',[
            'property' => "active_home" // Sert pour mettre la classe Active sur Accueil dans la nav bar
        ]);
    }
}