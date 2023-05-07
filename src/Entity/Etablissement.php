<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\EtablissementRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EtablissementRepository::class)]
#[ApiResource]
class Etablissement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $nomEtablissement = null;

    #[ORM\Column(length: 50)]
    private ?string $nomUniversite = null;

    #[ORM\Column(length: 20)]
    private ?string $ville = null;

    #[ORM\OneToMany(mappedBy: 'appartenir', targetEntity: CycleEtude::class, orphanRemoval: true)]
    private Collection $cycleEtudes;

    public function __construct()
    {
        $this->cycleEtudes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomEtablissement(): ?string
    {
        return $this->nomEtablissement;
    }

    public function setNomEtablissement(string $nomEtablissement): self
    {
        $this->nomEtablissement = $nomEtablissement;

        return $this;
    }

    public function getNomUniversite(): ?string
    {
        return $this->nomUniversite;
    }

    public function setNomUniversite(string $nomUniversite): self
    {
        $this->nomUniversite = $nomUniversite;

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

    /**
     * @return Collection<int, CycleEtude>
     */
    public function getCycleEtudes(): Collection
    {
        return $this->cycleEtudes;
    }

    public function addCycleEtude(CycleEtude $cycleEtude): self
    {
        if (!$this->cycleEtudes->contains($cycleEtude)) {
            $this->cycleEtudes->add($cycleEtude);
            $cycleEtude->setAppartenir($this);
        }

        return $this;
    }

    public function removeCycleEtude(CycleEtude $cycleEtude): self
    {
        if ($this->cycleEtudes->removeElement($cycleEtude)) {
            // set the owning side to null (unless already changed)
            if ($cycleEtude->getAppartenir() === $this) {
                $cycleEtude->setAppartenir(null);
            }
        }

        return $this;
    }
}
