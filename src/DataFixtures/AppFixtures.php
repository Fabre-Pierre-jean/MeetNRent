<?php

namespace App\DataFixtures;

use App\Entity\Ad;
use App\Entity\Image;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr-FR'); //use french language, see faker doc on github to see all the awesome fonctionnalities

        $users = [];
        $genres = ['male', 'female'];


        // Gestion des users
        for( $i=1; $i<=10; $i++ ){
            $user = new User();

           $genre = $faker->randomElement($genres);

           $urlBase = 'https://randomuser.me/api/portraits/';
           $pictureId = $faker->numberBetween(1,99) . '.jpg';
           $picture = $urlBase . ($genre == 'male' ? 'men/' :' women/' ) . $pictureId;

           $passwordHashed = $this->encoder->encodePassword($user, 'password');

            $user->setFirstName($faker->firstname($genre))
                ->setLastName($faker->lastname)
                ->setDescription('<p>'. join('</p><p>', $faker->paragraphs(3)). '</p>')
                ->setIntroduction($faker->sentence())
                ->setEmail($faker->email)
                ->setPasswordHash($passwordHashed)
                ->setPicture($picture)
                ->setPseudo($faker->userName);

            $manager->persist($user);

            $users[] = $user;
        }

        // Gestion des annonces
        for( $i=1; $i<=30 ; $i++){ //we want 30 ads

            $title = $faker->sentence(); //random words between 1 and 6 - default option is 6
            $coverImage = $faker->imageUrl(1000,350); // random image from website lorempixel
            $intro = $faker->paragraph(2);
            $content = '<p>'. join('</p><p>', $faker->paragraphs(5)). '</p>'; // paragraphs return an array so we separate each index with </p><p>

            $user = $users[mt_rand(0, count($users) - 1)];

            $ad = new Ad();

            $ad ->setTitle($title)
                ->setIntroduction($intro)
                ->setContents($content)
                ->setCoverImage($coverImage)
                ->setPrice(mt_rand(40, 200))//random integer between 40 and 200
                ->setRooms(mt_rand(1,4))
                ->setAuthor($user);

            //gestion des images supplémentaires d'une annonce
            for($j=1;$j<=mt_rand(2,5);$j++){
                $image = new Image();

                $image  ->setUrl($faker->imageUrl())
                        ->setCaption($faker->sentence())
                        ->setAd($ad);
                $manager->persist($image);
            }

            $manager->persist($ad);
        }

        $manager->flush();
    }
}
