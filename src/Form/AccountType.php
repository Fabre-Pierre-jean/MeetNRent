<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AccountType extends ApplicationType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('pseudo',
                TextType::class,
                $this->getOption(
                    "Pseudo",
                    "Entrez votre pseudo"
                )
            )
            ->add('lastName',
                TextType::class,
                $this->getOption(
                    "Votre nom de famille",
                    "Entrez votre nom de famille"
                )
            )
            ->add('firstName',
                TextType::class,
                $this->getOption(
                    "Votre prénom",
                    "Entrez votre prénom"
                )
            )
            ->add('email',
                EmailType::class,
                $this->getOption(
                    "Votre email",
                    "Entrez votre email"
                )
            )
            ->add('picture',
                UrlType::class,
                $this->getOption(
                    "Photo de profil",
                    "URL de votre photo de profil"
                )
            )

            ->add('introduction',
                TextType::class,
                $this->getOption(
                    "Résumé vous en une phrase",
                    "Entrez une brève description"
                )
            )
            ->add('description',
                TextareaType::class,
                $this->getOption(
                    "Dites nous en plus sur vous",
                    " Votre Description"
                )
            );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
