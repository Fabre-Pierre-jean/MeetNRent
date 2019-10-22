<?php


namespace App\Form\DataTransformer;


use Symfony\Component\Form\Exception\TransformationFailedException;
use Symfony\Component\Form\DataTransformerInterface;
/**
 * Permet de transformer notre datetime au bon format
 *
 * Class DateTimeToString
 * @package App\DataTransformer
 */
class TransformerDateTimeToString implements DataTransformerInterface
{
    /**
     * Permet de convertir une datetime en string format date FR
     */
    public function transform($date){

        if ($date === null){
            return '';
        }

        return $date->format('d/m/Y');
    }

    /**
     * Permet de convertir une string date FR en datetime
     */
    public function reverseTransform($frenchDate){
        if ($frenchDate === null){
            throw new TransformationFailedException("Error no date"); //le message ne sera jamais visible...
        }

        $date = \DateTime::createFromFormat('d/m/Y', $frenchDate);

        //la fonction createFromFormat renverra false si elle n'arrive pas a convertir en datetime
        // si mauvais format comme 19-02-18 par exemple

        if ($date === false){
            throw new TransformationFailedException("Error the format date isn't correct");
        }

        return $date;
    }
}