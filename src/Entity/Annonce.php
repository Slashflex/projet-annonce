<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Cocur\Slugify\Slugify;

// Gérer l'insertion des champs
use Symfony\Component\Validator\Constraints as Assert;
// Gère si un champ donné n'est pas déjà existant en base de données
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AnnonceRepository")
 * @ORM\HasLifecycleCallbacks()
 * @UniqueEntity(
 *     fields={"titre"},
 *     message="Le titre que vous avez entré existe déjà en base de données"
 * )
 */
class Annonce
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=60, nullable=true)
     * @Assert\Length(
     *      min=10,
     *      max=60,
     *      minMessage="Vous devez entrer au minimum {{ limit }} caractères",
     *      maxMessage="Vous devez entrer moins de {{ limit }} caractères"
     * )
     */
    private $titre;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $slug;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $prix;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Assert\Length(
     *      min=20,
     *      max=300,
     *      minMessage="Vous devez entrer au minimum {{ limit }} caractères",
     *      maxMessage="Vous devez entrer moins de {{ limit }} caractères"
     * )
     */
    private $introduction;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Assert\Length(
     *      min=20,
     *      max=300,
     *      minMessage="Vous devez entrer au minimum {{ limit }} caractères",
     *      maxMessage="Vous devez entrer moins de {{ limit }} caractères"
     * )
     */
    private $contenu;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\Url(message="L'url '{{ value }}' n'est pas valide")
     */
    private $imageCouverture;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Image", mappedBy="annonce")
     */
    private $images;

    public function __construct()
    {
        $this->images = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }
    public function setTitre(string $titre): self
    {
        $this->titre = $titre;

        return $this;
    }

    /**
     * @Orm\PrePersist
     * @ORM\PreUpdate
     */
    public function initializeSlug()
    {
        if (empty($this->slug))
        {
            $slug = new Slugify();
            $this->slug = $slug->slugify($this->titre);
        }
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


    public function getPrix(): ?float
    {
        return $this->prix;
    }
    public function setPrix(?float $prix): self
    {
        $this->prix = $prix;

        return $this;
    }


    public function getIntroduction(): ?string
    {
        return $this->introduction;
    }
    public function setIntroduction(?string $introduction): self
    {
        $this->introduction = $introduction;

        return $this;
    }


    public function getContenu(): ?string
    {
        return $this->contenu;
    }
    public function setContenu(string $contenu): self
    {
        $this->contenu = $contenu;

        return $this;
    }


    public function getImageCouverture(): ?string
    {
        return $this->imageCouverture;
    }
    public function setImageCouverture(?string $imageCouverture): self
    {
        $this->imageCouverture = $imageCouverture;

        return $this;
    }

    /**
     * @return Collection|Image[]
     */
    public function getImages(): Collection
    {
        return $this->images;
    }
    
    public function addImage(Image $image): self
    {
        if (!$this->images->contains($image)) {
            $this->images[] = $image;
            $image->setAnnonce($this);
        }

        return $this;
    }

    public function removeImage(Image $image): self
    {
        if ($this->images->contains($image)) {
            $this->images->removeElement($image);
            // set the owning side to null (unless already changed)
            if ($image->getAnnonce() === $this) {
                $image->setAnnonce(null);
            }
        }

        return $this;
    }
}
