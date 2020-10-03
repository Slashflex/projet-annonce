<?php

namespace App\Controller;

use App\Entity\Annonce;
use App\Form\AnnonceType;
use App\Repository\AnnonceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AdminAnnonceController extends AbstractController
{
    /**
     * @Route("/annonces", name="admin_annonces")
     */
    public function index(AnnonceRepository $annonceRepo, PaginatorInterface $paginator, Request $request)
    {
        $annonces = $annonceRepo->findAll();
        $pagination = $paginator->paginate(
            $annonces,
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('admin/annonce/index.html.twig', [
            'annonces' => $pagination
        ]);
    }


    /**
     * @Route("/annonces/{id}", name="admin_editer_annonces")
     */
    public function editerAnnonce(Annonce $annonce, Request $request, EntityManagerInterface $manager)
    {
        $form = $this->createForm(AnnonceType::class, $annonce);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($annonce);
            $manager->flush();

            return $this->redirectToRoute('admin_annonces');
        }

        return $this->render('admin/annonce/editer-annonce.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
