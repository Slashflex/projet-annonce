<?php

namespace App\Controller;

use App\Entity\Annonce;
use App\Form\AnnonceType;
use App\Repository\AnnonceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
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

    // /**
    //  * @Route("/envoyer-annonce", name="envoyer_annonce")
    //  */
    // public function envoyerAnnonce()
    // {
    //     for ($i = 0; $i < 10; $i++) 
    //     {
    //         $annonces = new Annonce();
    //         $annonces
    //             ->setTitre('Annonce' . $i)
    //             ->setSlug('slug-annonce' . $i)
    //             ->setContenu('message de test' . $i);

    //         // Data persistance
    //         $entityManager = $this->getDoctrine()->getManager();
    //         $entityManager->persist($annonces);
    //     }
    //     $entityManager->flush();

    //     return new Response("c'est ok");
    // }

    /**
     * Ici on affiche une liste de nos annonces
     * 
     * @Route("/afficher-annonces", name="afficher_annonces")
     */
    public function afficherAnnonces()
    {
        $annonces = $this->annonceRepository->findAll();

        return $this->render('front/annonce/index.html.twig', [
            'annonces' => $annonces
        ]);
    }

    /**
     * Créer une annonce
     *
     * @Route("/new", name="creer_annonce")
     */
    public function create(Request $request, EntityManagerInterface $manager)
    {
        $annonce = new Annonce();

        $form = $this->createForm(AnnonceType::class, $annonce);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $manager->persist($annonce);
            $manager->flush();
            
            $this->addFlash(
                'success',
                'Votre annonce a bien été créee'
            );

            return $this->redirectToRoute('afficher_annonce', [
                'slug' => $annonce->getSlug()
            ]);
        }

        return $this->render('front/annonce/create.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * Affiche une annonce
     * 
     * @Route("/{slug}", name="afficher_annonce")
     */
    public function show(Annonce $annonce)
    {
        return $this->render('front/annonce/show.html.twig', [
            'annonce' => $annonce,
        ]);
    }
}
