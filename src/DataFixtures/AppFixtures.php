<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Annonce;
use App\Entity\Image;
use Cocur\Slugify\Slugify;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        for ($i = 1; $i <= 20; $i++) 
        {
            $faker = Factory::create('fr_FR');
            $slug = new Slugify();
            $annonce = new Annonce();
            $contenu = '<p>' . join('</p><p>',$faker->paragraphs(3)) . '</p>';
            $titre = $faker->sentence(3);
            $imageCouverture = "https://i.picsum.photos/id/". mt_rand(0, 1000) ."/1200/350.jpg";
            $introduction = $faker->paragraph(2);

            $annonce
                ->setTitre($titre)
                ->setImageCouverture($imageCouverture)
                ->setIntroduction($introduction)
                ->setContenu($contenu)
                ->setPrix(mt_rand(20, 100))
                ->setSlug($slug->slugify($annonce->getTitre()));
            
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
