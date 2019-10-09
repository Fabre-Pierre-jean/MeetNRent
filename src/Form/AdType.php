<?php

namespace App\Form;

use App\Entity\Ad;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title',
                TextType::class, [
                    "label"         => "Titre de l'annonce" ])
            ->add('price',
                TextType::class, [
                    "label"         => "Prix par nuit" ])
            ->add('introduction',
                TextType::class, [
                    "label"         => "Image principale de l'annonce" ])
            ->add('coverImage',
                TextType::class, [
                    "label"         => "Image principale de l'annonce" ])
            ->add('rooms',
                TextType::class, [
                        "label"         => "Nombre(s) de chambre(s)" ])
            ->add('contents',
                TextareaType::class, [
                    'attr' => ['rows' => '15'],
                    "label"         => "Description" ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Ad::class,
        ]);
    }
}
