<?php

namespace App\Controller;

use App\Entity\Annonce;
use App\Repository\AnnonceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AnnonceController extends AbstractController
{
    private $manager;
    private $annonceRepository;

    public function __construct(AnnonceRepository $annonceRepository, EntityManagerInterface $manager)
    {
        $this->annonceRepository = $annonceRepository;
        $this->manager = $manager;
    }

    /**
     * @Route("/envoyer-annonce", name="envoyer_annonce")
     */
    public function envoyerAnnonce()
    {
        for ($i = 0; $i < 10; $i++) {
            $annonce = new Annonce();
            $annonce
                ->setTitre('Annonce' . $i)
                ->setSlug('slug-annonce' . $i)
                ->setContenu('message de test' . $i);

            // Data persistance
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($annonce);
        }
        $entityManager->flush();

        return new Response("c'est ok");
    }

    /**
     * @Route("/afficher-annonce", name="afficher_annonce")
     */
    public function afficherAnnonce()
    {
        $repository = $this->getDoctrine()->getRepository(Annonce::class);
        $annonce = $this->annonceRepository->findAll();

        return $this->render('annonce/index.html.twig', [
            'annonce' => $annonce
        ]);
    }
}
