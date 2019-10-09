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

    /**
     * @Route("/ads/{slug}", name="ads_show")
     *
     * @return Response
     */
    public function show(Ad $ad){ //on utilise le @ParamConverter pour convertir le {slug} en annonce, c'est à dire qu'il prend la méthode getSlug de l'entité Ad
        return $this->render('ad/show.html.twig', [
            'ad' => $ad
        ]);
    }
}