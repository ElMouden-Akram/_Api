<?php

namespace App\Entity;

use App\Repository\SecteurActiviteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SecteurActiviteRepository::class)]
class SecteurActivite
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $nomSecteur = null;

    #[ORM\ManyToMany(targetEntity: Entreprise::class, mappedBy: 'secteurActivite')]
    private Collection $entreprises;

    public function __construct()
    {
        $this->entreprises = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomSecteur(): ?string
    {
        return $this->nomSecteur;
    }

    public function setNomSecteur(string $nomSecteur): self
    {
        $this->nomSecteur = $nomSecteur;

        return $this;
    }

    /**
     * @return Collection<int, Entreprise>
     */
    public function getEntreprises(): Collection
    {
        return $this->entreprises;
    }

    public function addYe(Entreprise $ye): self
    {
        if (!$this->entreprises->contains($ye)) {
            $this->entreprises->add($ye);
            $ye->addSecteurActivite($this);
        }

        return $this;
    }

    public function removeEntreprises(Entreprise $Entreprises): self
    {
        if ($this->entreprises->removeElement($Entreprises)) {
            $Entreprises->removeSecteurActivite($this);
        }

        return $this;
    }
}
