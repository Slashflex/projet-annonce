<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Annonce;
use App\Entity\Image;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        for ($i = 1; $i <= 20; $i++) 
        {
            $faker = Factory::create('fr_FR');
            $annonce = new Annonce();
            $titre = $faker->sentence();
            $imageCouverture = "https://i.picsum.photos/id/". mt_rand(0, 1000) ."/1200/350.jpg";
            $introduction = $faker->paragraph(2);

            $annonce
                ->setTitre($titre)
                ->setImageCouverture($imageCouverture)
                ->setIntroduction($introduction);
            
            for ($j = 1; $j <= mt_rand(1, 10); $j++)
            {
                $image = new Image();
                $image
                    ->setUrl("https://i.picsum.photos/id/". mt_rand(0, 1000) ."/640/480.jpg")
                    ->setLegende($faker->sentence())
                    ->setAnnonce($annonce);
                
                $manager->persist($image);
            }    

            $manager->persist($annonce);
        }

        $manager->flush();
    }
}
