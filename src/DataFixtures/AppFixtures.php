<?php

namespace App\DataFixtures;

use App\Entity\Ad;
use App\Entity\Image;
use App\Entity\Role;
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

        //Gestion users que l'on met dans un array pour pouvoir les attribuer a une ad juste apres
        $users = [];

        //array pour choisir apres de façon random qu'un user soit ou male ou female
        $genres = ['male', 'female'];

        //Création d'un role admin
        $adminRole = new Role();
        $adminRole->setTitle('ROLE_ADMIN');
        $manager->persist($adminRole);

        //création d'un admin
        $adminUser = new User();

        $adminUser
                    ->setPseudo('PJ')
                    ->setEmail('pj@symfony.com')
                    ->setIntroduction($faker->sentence())
                    ->setDescription('<p>'. join('</p><p>', $faker->paragraphs(3)). '</p>')
                    ->setIntroduction('Symfony rocks!!')
                    ->setFirstName('Pierre-Jean')
                    ->setLastName('Fabre')
                    ->setPicture('https://avatars3.githubusercontent.com/u/54306081?s=400&u=c5234f443bb0e3786839ef081fbb871376cf1964&v=4')
                    ->setPasswordHash($this->encoder->encodePassword($adminUser,'password123'))
                    ->addUserRole($adminRole);
        $manager->persist($adminUser);


        // Gestion des users
        for( $i=1; $i<=20; $i++ ){
            $user = new User();

           $genre = $faker->randomElement($genres);

           $urlBase = 'https://randomuser.me/api/portraits/';
           $pictureId = $faker->numberBetween(1,99) . '.jpg';
           $picture = $urlBase . ($genre == 'male' ? 'men/' :'women/' ) . $pictureId;

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
        for( $i=1; $i<=100 ; $i++){ //we want 30 ads

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
