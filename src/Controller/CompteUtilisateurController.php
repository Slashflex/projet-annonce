<?php

namespace App\Controller;

use App\Entity\MiseAJourMotDePasse;
use App\Entity\Utilisateur;
use App\Form\CompteUtilisateurType;
use App\Form\MiseAJourMotDePasseType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

// Security & isGranted pour gérer les roles
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/compte")
 */
class CompteUtilisateurController extends AbstractController
{
    /**
     * @Route("/login", name="connexion_compte_utilisateur")
     */
    public function connexion()
    {
        return $this->render('utilisateur/connexion.html.twig');
    }

    /**
     * @Route("/deconnexion", name="deconnexion_compte_utilisateur")
     */
    public function deconnexion() {}

    /**
     * @Route("/modifier-profile", name="modifier_profile")
     * @IsGranted("ROLE_USER")
     */
    public function modifierProfilUtilisateur(Request $request, EntityManagerInterface $entityManager)
    {
        $user = $this->getUser();
        $form = $this->createForm(CompteUtilisateurType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash(
                'success',
                'Vos informations ont bien été modifiées'
            );
        }

        return $this->render('utilisateur/modifier-profile.html.twig', [
            'form' => $form->createView(),
            'user' => $user
        ]);
    }

    /**
     * @Route("/modifier-mot-de-passe", name="modifier_mot_de_passe")
     * @IsGranted("ROLE_USER")
     */
    public function modifierMotDePasse(Request $request, EntityManagerInterface $entityManager, UserPasswordEncoderInterface $passwordEncoder)
    {
        $user = $this->getUser();
        $majMotDePasse = new MiseAJourMotDePasse();
        
        $form = $this->createForm(MiseAJourMotDePasseType::class, $majMotDePasse);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if (!password_verify($majMotDePasse->getAncienMotDePasse(), $user->getPassword())) {
                $form->get('ancienMotDePasse')->addError(new FormError('Ce mot de passe est différent du votre'));
            } else {
                $nouveauMotDePasse = $majMotDePasse->getNouveauMotDePasse();
                $hash = $passwordEncoder->encodePassword($user, $nouveauMotDePasse);
                $user->setMotDePasse($hash);

                $entityManager->persist($user);
                $entityManager->flush();
    
                $this->addFlash(
                    'success',
                    'Votre mot de passe a bien été modifié'
                );

                return $this->redirectToRoute('afficher_utilisateur', [
                    'slug' => $user->getSlug()
                ]);
            }
        } 

        return $this->render('utilisateur/modifier_mot_de_passe.html.twig', [
            'title' => 'Modifiez votre mot de passe',
            'form' => $form->createView()
        ]);
    }
}
