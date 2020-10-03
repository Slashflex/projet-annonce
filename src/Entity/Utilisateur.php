<?php

namespace App\Entity;

use Cocur\Slugify\Slugify;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

// Gérer l'insertion des champs
use Symfony\Component\Validator\Constraints as Assert;
// Gère si un champ donné n'est pas déjà existant en base de données
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
/**
 * @ORM\Entity(repositoryClass="App\Repository\UtilisateurRepository")
 * @ORM\HasLifecycleCallbacks()
 * @UniqueEntity(fields={"email"}, message="There is already an account with this email")
 */
class Utilisateur implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(
     *      min=3,
     *      max=60,
     *      minMessage="Vous devez entrer au minimum {{ limit }} caractères",
     *      maxMessage="Vous devez entrer moins de {{ limit }} caractères"
     * )
     */
    private $prenom;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $avatar;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $motDePasse;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @Orm\PrePersist
     * @ORM\PreUpdate
     */
    public function initializeSlug()
    {
        if (empty($this->slug))
        {
            $slug = new Slugify();
            $this->slug = $slug->slugify($this->prenom .' '.$this->nom);
        }
    }
    /**
     * @ORM\Column(type="string", length=255)
     */
    private $slug;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Annonce", mappedBy="auteur", orphanRemoval=true)
     */
    private $annonces;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Role", mappedBy="utilisateurs")
     */
    private $rolesUtilisateur;

    public function __construct()
    {
        $this->annonces = new ArrayCollection();
        $this->rolesUtilisateur = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }


    public function getPrenom(): ?string
    {
        return $this->prenom;
    }
    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }


    public function getNom(): ?string
    {
        return $this->nom;
    }
    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getNomComplet()
    {
        return "$this->nom $this->prenom"; 
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }
    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }
    public function getUsername() 
    {
        return $this->email;
    }


    public function getAvatar(): ?string
    {
        return $this->avatar;
    }
    public function setAvatar(?string $avatar): self
    {
        $this->avatar = $avatar;

        return $this;
    }


    public function getMotDePasse(): ?string
    {
        return $this->motDePasse;
    }
    public function getPassword()
    {
        return $this->motDePasse;
    }
    public function setMotDePasse(string $motDePasse): self
    {
        $this->motDePasse = $motDePasse;

        return $this;
    }

    public function getSalt() {}

    public function eraseCredentials() {}

    public function getRoles()
    {
        $roles = $this->rolesUtilisateur->map(function($role) {
            return $role->getTitre();
        })->toArray();

        $roles[] = 'ROLE_USER';
        return $roles;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }
    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    
    public function getSlug(): ?string
    {
        return $this->slug;
    }
    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }


    /**
     * @return Collection|Annonce[]
     */
    public function getAnnonces(): Collection
    {
        return $this->annonces;
    }

    public function addAnnonce(Annonce $annonce): self
    {
        if (!$this->annonces->contains($annonce)) {
            $this->annonces[] = $annonce;
            $annonce->setAuteur($this);
        }

        return $this;
    }

    public function removeAnnonce(Annonce $annonce): self
    {
        if ($this->annonces->contains($annonce)) {
            $this->annonces->removeElement($annonce);
            // set the owning side to null (unless already changed)
            if ($annonce->getAuteur() === $this) {
                $annonce->setAuteur(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Role[]
     */
    public function getRolesUtilisateur(): Collection
    {
        return $this->rolesUtilisateur;
    }

    public function addRolesUtilisateur(Role $rolesUtilisateur): self
    {
        if (!$this->rolesUtilisateur->contains($rolesUtilisateur)) {
            $this->rolesUtilisateur[] = $rolesUtilisateur;
            $rolesUtilisateur->addUtilisateur($this);
        }

        return $this;
    }

    public function removeRolesUtilisateur(Role $rolesUtilisateur): self
    {
        if ($this->rolesUtilisateur->contains($rolesUtilisateur)) {
            $this->rolesUtilisateur->removeElement($rolesUtilisateur);
            $rolesUtilisateur->removeUtilisateur($this);
        }

        return $this;
    }
}
