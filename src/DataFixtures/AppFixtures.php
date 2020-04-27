<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Image;
use App\Entity\Annonce;
use Cocur\Slugify\Slugify;
use App\Entity\Utilisateur;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');
        $sexes = ['male', 'female'];
        $users = [];

        for ($i = 1; $i <= 6; $i++) 
        {
            $user = new Utilisateur();
            $avatar = "https://randomuser.me/api/portraits/";
            $sexe = $faker->randomElement($sexes);
            $photoId = $faker->numberBetween(1, 99) . '.jpg';
            $avatar .= ($sexe == 'male' ? 'men/' : 'women/') . $photoId;
            $hash = $this->passwordEncoder->encodePassword($user, 'mdp');

            $user
                ->setPrenom($faker->firstName($sexe))
                ->setNom($faker->lastName)
                ->setEmail($faker->email)
                ->setAvatar($avatar)
                ->setMotDePasse($hash)
                ->setDescription($faker->paragraph(2));
            
            $manager->persist($user);
            $users[] = $user;
        }

        for ($i = 1; $i <= 20; $i++) 
        {
            $slug = new Slugify();
            $annonce = new Annonce();
            $contenu = '<p>' . join('</p><p>',$faker->paragraphs(3)) . '</p>';
            $titre = $faker->sentence(3);
            $imageCouverture = "https://i.picsum.photos/id/". mt_rand(0, 1000) ."/1200/350.jpg";
            $introduction = $faker->paragraph(2);

            $user = $users[mt_rand(0, count($users) - 1)];

            $annonce
                ->setTitre($titre)
                ->setImageCouverture($imageCouverture)
                ->setIntroduction($introduction)
                ->setContenu($contenu)
                ->setPrix(mt_rand(20, 100))
                ->setAuteur($user);
            
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
