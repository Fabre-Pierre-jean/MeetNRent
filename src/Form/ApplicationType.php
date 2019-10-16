<?php


namespace App\Form;


use Symfony\Component\Form\AbstractType;

class ApplicationType extends AbstractType
{
    /**
     * Permet de ne pas se repeter
     *
     * @param $label
     * @param $placeholder
     * @return array
     */
    protected function getOption($label, $placeholder){
        return [
            'attr'          => ['placeholder' => "$placeholder"],
            "label"         => "$label"
        ];
    }


}