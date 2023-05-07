<?php

namespace App\Entity;

use App\Repository\EntrepriseRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EntrepriseRepository::class)]
class Entreprise
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 20)]
    private ?string $nom = null;

    #[ORM\Column(length: 30)]
    private ?string $siteweb = null;

    #[ORM\Column(length: 20)]
    private ?string $ville = null;

    #[ORM\Column(length: 20)]
    private ?string $pays = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\ManyToMany(targetEntity: SecteurActivite::class, inversedBy: 'entreprises')]
    private Collection $secteurActivite;

    #[ORM\OneToMany(mappedBy: 'appartenir', targetEntity: Emploi::class, orphanRemoval: true)]
    private Collection $emplois;

    public function __construct()
    {
        $this->secteurActivite = new ArrayCollection();
        $this->emplois = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getSiteweb(): ?string
    {
        return $this->siteweb;
    }

    public function setSiteweb(string $siteweb): self
    {
        $this->siteweb = $siteweb;

        return $this;
    }

    public function getVille(): ?string
    {
        return $this->ville;
    }

    public function setVille(string $ville): self
    {
        $this->ville = $ville;

        return $this;
    }

    public function getPays(): ?string
    {
        return $this->pays;
    }

    public function setPays(string $pays): self
    {
        $this->pays = $pays;

        return $this;
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

    /**
     * @return Collection<int, SecteurActivite>
     */
    public function getSecteurActivite(): Collection
    {
        return $this->secteurActivite;
    }

    public function addSecteurActivite(SecteurActivite $secteurActivite): self
    {
        if (!$this->secteurActivite->contains($secteurActivite)) {
            $this->secteurActivite->add($secteurActivite);
        }

        return $this;
    }

    public function removeSecteurActivite(SecteurActivite $secteurActivite): self
    {
        $this->secteurActivite->removeElement($secteurActivite);

        return $this;
    }

    /**
     * @return Collection<int, Emploi>
     */
    public function getEmplois(): Collection
    {
        return $this->emplois;
    }

    public function addEmploi(Emploi $emploi): self
    {
        if (!$this->emplois->contains($emploi)) {
            $this->emplois->add($emploi);
            $emploi->setAppartenir($this);
        }

        return $this;
    }

    public function removeEmploi(Emploi $emploi): self
    {
        if ($this->emplois->removeElement($emploi)) {
            // set the owning side to null (unless already changed)
            if ($emploi->getAppartenir() === $this) {
                $emploi->setAppartenir(null);
            }
        }

        return $this;
    }
}
