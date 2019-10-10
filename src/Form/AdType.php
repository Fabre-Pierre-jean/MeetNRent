<?php

namespace App\Form;

use App\Entity\Ad;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdType extends AbstractType
{
    /**
     * Permet de ne pas se repeter
     *
     * @param $label
     * @param $placeholder
     * @return array
     */
    private function getOption($label, $placeholder){
        return [
            'attr'          => ['placeholder' => "$placeholder"],
            "label"         => "$label"
        ];
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'title',
                TextType::class,
                 $this->getOption(
                     "Titre de l'annonce",
                     "Titre de l'annonce"
                 )
            )

            ->add(
                'price',
                MoneyType::class,
                $this->getOption(
                    "Prix par nuit",
                    "Prix par nuit"
                )
            )

            ->add(
                'introduction',
                TextType::class,
                $this->getOption(
                    "Décrivez votre annonce en une phrase",
                    "Introduction"
                )
            )

            ->add(
                'coverImage',
                TextType::class,
                $this->getOption(
                    "Entrez l'URL de votre image principale",
                    "Image principale de l'annonce"
                )
            )

            ->add(
                'rooms',
                NumberType::class,[
                    'html5'       => true,
                    'attr'          => ['placeholder' => "Nombre(s) de chambre(s)"],
                    'label'         => 'Nombre(s) de chambre(s) disponible'
                ]
            )

            ->add(
                'contents',
                TextareaType::class, [
                    'attr'          => [
                                        'rows'        => '5',
                                        'placeholder' => "Faites une description détaillée de votre annonce" ],
                                        "label"         => "Description"
                ]
            )

//            ->add('images', CollectionType::class,
//                [
//                    'entry_type' => ImageType::class
//                ]
//                )
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Ad::class,
        ]);
    }
}
