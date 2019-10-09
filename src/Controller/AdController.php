<?php

namespace App\Controller;

use App\Entity\Ad;
use App\Form\AdType;
use App\Repository\AdRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdController extends AbstractController
{
    /**
     * @Route("/ads", name="ads_index")
     */
    public function index(AdRepository $em)
    {
        $ads = $em->findAll(); // This method is possible because you inject the dependency in parameter to the index function, the dependency is AdRepository

        return $this->render('ad/index.html.twig', [
            'ads' => $ads,
            'property' => "active_ad"
        ]);
    }

    /**
     * @Route("/ads/new", name="ads_new")
     * @return Response
     */
    public function create(Request  $request, ObjectManager $manager)
    {
        $ad = new Ad();
        $form = $this->createForm(AdType::class, $ad);
        $form->handleRequest($request);

        if ($form->isSubmitted()&& $form->isValid() ) {
//            $manager = $this->getDoctrine()->getManager(); No more necessarly
            $manager->persist($ad);
            $manager->flush();

            $this->addFlash(
                'success',
                "L'annonce {$ad->getTitle()} a bien été crée !"
            );

            return $this->redirectToRoute('ads_show', [
                'slug' => $ad->getSlug()
            ]);
        }

        return $this->render('ad/create.html.twig',[
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/ads/{slug}", name="ads_show")
     *
     * @return Response
     */
    public function show(Ad $ad){
        return $this->render('ad/show.html.twig', [
           'ad' => $ad
        ]);
    }

}
