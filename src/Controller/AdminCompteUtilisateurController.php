<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;


/**
 * @Route("/admin")
 */
class AdminCompteUtilisateurController extends AbstractController
{
    /**
     * @Route("/connexion", name="connexion_compte_utilisateur_admin")
     */
    public function index(AuthenticationUtils $authenticationUtils)
    {
        $error = $authenticationUtils->getLastAuthenticationError(); // get the login error if there is one
        $lastUsername = $authenticationUtils->getLastUsername(); // last username entered by the user

        return $this->render('admin/connexion.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error
        ]);
    }
}
