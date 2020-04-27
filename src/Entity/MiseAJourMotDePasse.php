<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;

class MiseAJourMotDePasse
{
    
    private $ancienMotDePasse;
    
    /**
     * @Assert\Length(
     *      min=6,
     *      minMessage="Votre mot de passe doit contenir au minimum {{ limit }} caractères"
     * )
     */
    private $nouveauMotDePasse;

    /**
     * @Assert\EqualTo(
     *      propertyPath="nouveauMotDePasse",
     *      message="Les deux mots de passe ne sont pas identique"
     * )
     */
    private $confirmationMotDePasse;

    public function getAncienMotDePasse(): ?string
    {
        return $this->ancienMotDePasse;
    }

    public function setAncienMotDePasse(string $ancienMotDePasse): self
    {
        $this->ancienMotDePasse = $ancienMotDePasse;

        return $this;
    }

    public function getNouveauMotDePasse(): ?string
    {
        return $this->nouveauMotDePasse;
    }

    public function setNouveauMotDePasse(string $nouveauMotDePasse): self
    {
        $this->nouveauMotDePasse = $nouveauMotDePasse;

        return $this;
    }

    public function getConfirmationMotDePasse(): ?string
    {
        return $this->confirmationMotDePasse;
    }

    public function setConfirmationMotDePasse(string $confirmationMotDePasse): self
    {
        $this->confirmationMotDePasse = $confirmationMotDePasse;

        return $this;
    }
}
