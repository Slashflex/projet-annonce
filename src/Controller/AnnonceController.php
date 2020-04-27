<?php

namespace App\Controller;

use App\Entity\Annonce;
use App\Form\AnnonceType;
use App\Repository\AnnonceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/annonce")
 */
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
            foreach ($annonce->getImages() as $image)
            {
                $image->setAnnonce($annonce);
                $manager->persist($image);
            }

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
     * @Route("/{slug}/editer", name="editer_annonce")
     */
    public function edit(Annonce $annonce, Request $request, EntityManagerInterface $manager)
    {
        $form = $this->createForm(AnnonceType::class, $annonce);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            foreach ($annonce->getImages() as $image)
            {
                $image->setAnnonce($annonce);
                $manager->persist($image);
            }

            $manager->persist($annonce);
            $manager->flush();
            
            $this->addFlash(
                'success',
                'Votre annonce a bien été mise à jour'
            );

            return $this->redirectToRoute('afficher_annonce', [
                'slug' => $annonce->getSlug()
            ]);
        }

        return $this->render('front/annonce/edit.html.twig', [
            'form' => $form->createView(),
            'annonce' => $annonce
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
            'annonce' => $annonce
        ]);
    }

    /**
     * Supprime une annonce
     * 
     * @Route("/{slug}/supprimer", name="supprimer_annonce")
     */
    public function delete(EntityManagerInterface $manager, Annonce $annonce)
    {
        $manager->remove($annonce);
        $manager->flush();
        
        $this->addFlash(
            'success',
            'Votre annonce a bien été supprimée'
        );

        return $this->redirectToRoute('afficher_annonces');
    }
}
