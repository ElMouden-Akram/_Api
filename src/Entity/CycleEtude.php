<?php

namespace App\Entity;

use ApiPlatform\Metadata\Post;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiResource;
use App\Controller\cycleEtudes\AddEtudiant;
use App\Repository\CycleEtudeRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: CycleEtudeRepository::class)]
#[ApiResource(
    operations:[
        new Post(
            denormalizationContext:['groups'=>['CycleEtude:read','CycleEtude:write']],//response
            normalizationContext:['groups'=>['CycleEtude:read','CycleEtude:write']]//request  / #[Groups(['CycleEtude:write'])]
        ),
        new Post(),
        new Post(
            name: "addEtudiant",
            uriTemplate: '/cycle_etudes/{id}/addEtudiant',
            controller: AddEtudiant::class ,
            // write:false,
            // read: false,
            denormalizationContext:['groups'=>'addEtudiant'],//request
            normalizationContext:['groups'=>'addEtudiant'],//response
        ),
    ],
)]
class CycleEtude
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    #[Groups(['CycleEtude:write'])]
    private ?string $titre = null;

    #[ORM\Column]
    #[Groups(['CycleEtude:write'])]
    private ?bool $isMainStudy = null;

    #[ORM\Column(length: 50)]
    #[Groups(['CycleEtude:write'])]
    private ?string $discipline = null;

    #[ORM\Column(length: 50)]
    #[Groups(['CycleEtude:write'])]
    private ?string $diplome = null;

    #[ORM\ManyToOne(inversedBy: 'cycleEtudes')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['CycleEtude:write'])]
    private ?Etablissement $appartenir = null;

    #[ORM\ManyToMany(targetEntity: User::class, mappedBy: 'poursuivre')]
    private Collection $Etudiants;

    #[ORM\OneToMany(mappedBy: 'apartenir', targetEntity: Formation::class)]
    private Collection $formations;

    public function __construct()
    {
        $this->Etudiants = new ArrayCollection();
        $this->formations = new ArrayCollection();
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

    public function isIsMainStudy(): ?bool
    {
        return $this->isMainStudy;
    }

    public function setIsMainStudy(bool $isMainStudy): self
    {
        $this->isMainStudy = $isMainStudy;

        return $this;
    }

    public function getDiscipline(): ?string
    {
        return $this->discipline;
    }

    public function setDiscipline(string $discipline): self
    {
        $this->discipline = $discipline;

        return $this;
    }

    public function getDiplome(): ?string
    {
        return $this->diplome;
    }

    public function setDiplome(string $diplome): self
    {
        $this->diplome = $diplome;

        return $this;
    }

    public function getAppartenir(): ?Etablissement
    {
        return $this->appartenir;
    }

    public function setAppartenir(?Etablissement $appartenir): self
    {
        $this->appartenir = $appartenir;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getEtudiants(): Collection
    {
        return $this->Etudiants;
    }

    public function addEtudiant(User $etudiant): self
    {
        if (!$this->Etudiants->contains($etudiant)) {
            $this->Etudiants->add($etudiant);
            $etudiant->addPoursuivre($this);
        }

        return $this;
    }

    public function removeEtudiant(User $etudiant): self
    {
        if ($this->Etudiants->removeElement($etudiant)) {
            $etudiant->removePoursuivre($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Formation>
     */
    public function getFormations(): Collection
    {
        return $this->formations;
    }

    public function addFormation(Formation $formation): self
    {
        if (!$this->formations->contains($formation)) {
            $this->formations->add($formation);
            $formation->setApartenir($this);
        }

        return $this;
    }

    public function removeFormation(Formation $formation): self
    {
        if ($this->formations->removeElement($formation)) {
            // set the owning side to null (unless already changed)
            if ($formation->getApartenir() === $this) {
                $formation->setApartenir(null);
            }
        }

        return $this;
    }
}
