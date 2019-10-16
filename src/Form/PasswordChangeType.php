<?php

namespace App\Form;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class   PasswordChangeType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('oldPassword',
                PasswordType::class,
                $this->getOption(
                    "Entrez votre ancien mot de passe",
                    "Ancien mot de passe"
                )
            )
            ->add('newPassword',
                PasswordType::class,
                $this->getOption(
                    "Entrez votre nouveau mot de passe",
                    "Nouveau mot de passe"
                )
            )
            ->add('passwordConfirm',
                PasswordType::class,
                $this->getOption(
                    "Confirmez votre nouveau mot de passe",
                    "Confirmez le nouveau mot de passe"
                )
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
