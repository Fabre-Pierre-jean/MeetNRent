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
     * @param array $options
     * @return array
     */
    protected function getOption($label, $placeholder, $options = []){
        return array_merge([
            "label"         => "$label",
            'attr'          => [
                'placeholder' => "$placeholder"
            ]
        ],$options);
    }


}