<?php

namespace App\Controller;

use App\Entity\Ad;
use App\Form\AdType;
use App\Repository\AdRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdController extends AbstractController
{
    /**
     * @Route("/ads", name="ads_index")
     * @param AdRepository $em
     * @return Response
     */
    public function index(AdRepository $em)
    {
        $ads = $em->findAll(); // This method is possible because you inject the dependency in parameter to the index function, the dependency is AdRepository

        return $this->render('ad/index.html.twig', [
            'ads' => $ads,
            'property' => "active_ad",
            'user'  => $this->getUser()
        ]);
    }

    /**
     * Création d'une annonce
     *
     * @Route("/ads/new", name="ads_new")
     *
     * @IsGranted("ROLE_USER")
     *
     * @param Request $request
     * @param ObjectManager $manager
     * @return Response
     */
    public function create(Request  $request, ObjectManager $manager)
    {
        $ad = new Ad();
        $form = $this->createForm(AdType::class, $ad);
        $form->handleRequest($request);

        $ad->setAuthor($this->getUser()); //set l'auteur de l'annonce avec l'auteur qui est connecté

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
     * Voir une annonce
     *
     * @Route("/ads/{slug}", name="ads_show")
     *
     * @IsGranted("ROLE_USER")
     *
     * @param Ad $ad
     * @return Response
     */
    public function show(Ad $ad){ //ici on utilise le @ParamConverter afin de convertir le slug trouver en une annonce, Symfony prend le slug et va chercher l'annonce qui a ce slug
        return $this->render('ad/show.html.twig', [
            'ad'     => $ad,
            'user'  => $this->getUser()
        ]);
    }

    /**
     * @Route("/ads/{slug}/edit", name="ads_edit")
     *
     * @Security("is_granted('ROLE_USER') and user === ad.getAuthor()")
     *
     * @param Ad $ad
     * @param ObjectManager $manager
     * @param Request $request
     * @return Response
     */
    public function edit(Ad $ad, ObjectManager $manager, Request $request){
        $form = $this->createForm(AdType::class, $ad);
        $form->handleRequest($request);

        if ($form->isSubmitted()&& $form->isValid() ) {
//            $manager = $this->getDoctrine()->getManager(); No more necessarly grace à l'injection de dependance
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
            'form' => $form->createView(),
            'user' => $this->getUser()
        ]);
    }


    /**
     * Supprimer une annonce
     *
     * @Security("is_granted('ROLE_USER') and user === ad.getAuthor()")
     *
     * @Route("/ads/{slug}/delete", name="ad_delete")
     */
    public function deleteAction(Ad $ad, ObjectManager $manager) //La route sera donc {{ path('ad_delete', {'slug' : ad.slug}) }}
    {
            $manager->remove($ad);
            $manager->flush();


        $this->addFlash(
            'success',
            "L'annonce {$ad->getTitle()} a bien été supprimée !"
        );

        $router = $this->generateUrl('my_profile').'#my_ads';
        return $this->redirect($router);
    }

}
