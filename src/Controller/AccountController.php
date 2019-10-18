<?php

namespace App\Controller;

use App\Entity\PasswordChange;
use App\Entity\User;
use App\Form\AccountType;
use App\Form\PasswordChangeType;
use App\Form\RegistrationType;
use Doctrine\Common\Persistence\ObjectManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class AccountController extends AbstractController
{
    /**
     * Permet de se connecter
     *
     * @Route("/login", name="account_login")
     *
     * @return Response
     */
    public function login(AuthenticationUtils $authenticationUtils)
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('account/login.html.twig', [
            'last_username' => $lastUsername,
            'hasError' => $error
        ]);
    }

    /**
     * Permet de se déconnecter
     *
     * @IsGranted("ROLE_USER")
     *
     * @Route("/logout", name="account_logout")
     *
     * @return void
     */
    public function logout(){
        //on fait rien, il gère ça avec le security.yml dans la section logout
    }

    /**
     * Permet de s'inscrire
     *
     * @Route("/register", name="account_register")
     *
     * @return Response
     */
    public function register(Request $request, ObjectManager $manager, UserPasswordEncoderInterface $encoder){
        $user = new User();

        $form = $this->createForm(RegistrationType::class, $user);

        $form->handleRequest($request);

        if( $form->isSubmitted() && $form->isValid()){
            $passwordHashed = $encoder->encodePassword($user, $user->getPasswordHash());
            $user->setPasswordHash($passwordHashed);

            $manager->persist($user);
            $manager->flush();

            $this->addFlash(
                'success',
                "Votre profil {$user->getPseudo()} a bien été crée, veuillez vous connecter !"
            );
            return $this->redirectToRoute('account_logout');
        }

        return $this->render('account/register.html.twig',[
            'form' => $form->createView()
        ]);
    }


    /**
     * Modification du profil
     *
     * @Route("/profile", name="account_profile")
     *
     * @Security("is_granted('ROLE_USER')")
     *
     * @return Response
     */
    public function profile(Request $request, ObjectManager $manager){

        $user = $this->getUser();

        $form = $this->createForm(AccountType::class, $user);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $manager->persist($user);
            $manager->flush();

            $this->addFlash(
                'success',
                "Le profil de {$user->getFirstName()}   {$user->getLastName()} a bien été modifié"
            );
        }
        return $this->render('account/profile.html.twig',[
            'form' => $form->createView()
        ]);
    }

    /**
     * Modification du mot de passe
     *
     * @Route("/user/my_profile/password", name="account_password")
     *
     * @Security("is_granted('ROLE_USER')")
     *
     * @return Response
     */
    public function passwordChange(Request $request, ObjectManager $manager, UserPasswordEncoderInterface $encoder){

        $passwordChange = new PasswordChange();
        $form = $this->createForm(PasswordChangeType::class, $passwordChange);

        $form->handleRequest($request);

        $user = $this->getUser();

        if($form->isSubmitted() && $form->isValid()) {
            // we verify if the oldPassword correspond to the passwordHash in our db
            if (!password_verify($passwordChange->getOldPassword(), $user->getPasswordHash())) {
                $form->get('oldPassword')->addError(new FormError("Mauvais mot de passe !"));
            } else {
                $newPassword = $passwordChange->getNewPassword();
                $passwordHashed = $encoder->encodePassword($user,$newPassword);
                $user->setPasswordHash($passwordHashed);
                $manager->persist($user);
                $manager->flush();

                $this->addFlash(
                    'success',
                    "Votre mot de passe a bien été modifié !"
                );
                return $this->redirectToRoute('homepage');
            }
        }

        return $this->render('account/password.html.twig', [
            'form' => $form->createView()
    ]);
    }
 }
