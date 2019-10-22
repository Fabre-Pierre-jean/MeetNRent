<?php

namespace App\Form;

use App\Form\DataTransformer\TransformerDateTimeToString;
use App\Entity\Booking;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BookingType extends ApplicationType
{

    private $tranformer;

    public function __construct(TransformerDateTimeToString $tranformer)
    {
        $this->tranformer= $tranformer;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('startDate',
                TextType::class,
                $this->getOption(
                    "Date d'arrivée",
                    "Date à laquelle vous souhaitez arriver"
                ))
            ->add('endDate',
                TextType::class,
                $this->getOption(
                    "Date de départ",
                    "Date à laquelle vous souhaitez partir"
                ))
                        ->add('message',
                TextareaType::class,
                $this->getOption(
                    'Message à l\'intention du propriétaire',
                    'Une demande particulière ? C\'est ici que ça se passe',
                    [ 'required' => false ]
                ))
        ;

        $builder->get('startDate')->addModelTransformer($this->tranformer);
        $builder->get('endDate')->addModelTransformer($this->tranformer);


    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Booking::class,
        ]);
    }
}
