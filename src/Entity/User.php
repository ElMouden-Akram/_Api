<?php

namespace App\Entity;

// use ApiPlatform\Core\Annotation\ApiFilter;
use App\Entity\CycleEtude;
use ApiPlatform\Metadata\Get;
// use ApiPlatform\Core\Bridge\Doctrine\MongoDbOdm\Filter\SearchFilter;
use ApiPlatform\Metadata\Put;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Delete;
use App\Controller\MeController;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\UserRepository;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use Doctrine\Common\Collections\Collection;
use phpDocumentor\Reflection\Types\Boolean;
use Doctrine\Common\Collections\ArrayCollection;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\SerializedName;
use ApiPlatform\Core\Bridge\Doctrine\MongoDbOdm\Filter\BooleanFilter;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
// #[ORM\Table(name: '`user`')] doctrine sauvegarde par defaut dans la table du nom de la classe sinon il faut la specifier grace a cette ligne
#[ApiResource(
    
    // security: "is_granted('ROLE_ADMIN')"    ,
    // normalizationContext: ['groups' => ['user:read']],
    operations:[
        //🚧 to add section you wanna see in api-plateform:
        new Get(
            //🚧 on definie ici what we wanna have in get only :
            normalizationContext:['groups'=>['user:read']],
        ),
        //👇 pour ajouter "/api/me" :
        new Get(name: 'me',
                paginationEnabled:false, 
                uriTemplate: '/me', 
                controller: MeController::class,
                read:false, //https://api-platform.com/docs/core/controllers/#retrieving-the-entity +🎥: https://youtu.be/BhXFvTqByeQ?t=377
                normalizationContext:['groups'=>['user:read']],
                // security: "is_granted('ROLE_ETUDIANT')"
            ),
        new GetCollection(),
        new Post(
            denormalizationContext:['groups'=>['user:read','user:write']],
            normalizationContext:['groups'=>['user:read','user:write']]
        ),
        // new Put(),
        // new Delete(),
        // new Patch(),
    ],
    paginationItemsPerPage: 5,

    ),
     ApiFilter(
        SearchFilter::class,
        properties:['FirstName'=>'partial'], //🎥(highly recommended):https://youtu.be/ZRBRtA_2NAo?t=4273 
    )
    ]

class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['user:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    #[Groups(['user:write'])]
    private ?string $username = null;

    #[ORM\Column]
    #[Groups(['user:read'])]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    #[Groups(['user:read','user:write']),Assert\Length(8,20, minMessage:"minErrorMessage",maxMessage:"MaxErrorMessage")]
    private ?string $password = null;


    #[ORM\Column(length: 20, nullable: true)]
    #[Groups(['user:read'])]
    private ?string $FirstName = null;

    #[ORM\Column(length: 20, nullable: true)]
    #[Groups(['user:read'])]
    private ?string $LastName = null;

    #[ORM\Column(length: 10, nullable: true)]
    #[Groups(['user:read'])]
    private ?string $CNI = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['user:read'])]
    private ?int $numTelephone = null;

    #[ORM\Column(length: 10, nullable: true)]
    #[Groups(['user:read'])]
    private ?string $sexe = null;

    #[ORM\Column(length: 30, nullable: false)]
    #[Groups(['user:read'])]
    #[Assert\NotBlank(message:"Veuille saisir un email ."),
    ]
    private ?string $email = null;

    #[ORM\OneToMany(mappedBy: 'ajouterPar', targetEntity: OffreStage::class)]
    private Collection $offreStages;

    #[ORM\OneToMany(mappedBy: 'ajouterPar', targetEntity: OffreEmploi::class)]
    private Collection $offreEmplois;

    #[ORM\OneToMany(mappedBy: 'ajouterPar', targetEntity: Formation::class)]
    private Collection $formations;

    #[ORM\ManyToMany(targetEntity: CycleEtude::class, inversedBy: 'Etudiants')]
    private Collection $poursuivre;  //hadi tzadet because of OffreClient : chuf lteh kayna une methode tkhlik t3ref pour un user jami3 les offre le 3ndo

    public function __construct()
    {
        $this->offreStages = new ArrayCollection();
        $this->offreEmplois = new ArrayCollection();
        $this->formations = new ArrayCollection();
        $this->poursuivre = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->username;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_E
        $roles[] = 'ROLE_ETUDIANT';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {

        $this->password = $password;

        return $this;
    }

    // public function getPlainPassword(): string
    // {
    //     return $this->plainPassword;
    // }

    // public function setPlainPassword(?string $plainPassword): self
    // {

    //     $this->plainPassword = $plainPassword;

    //     return $this;
    // }



    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getFirstName(): ?string
    {
        return $this->FirstName;
    }

    public function setFirstName(?string $FirstName): self
    {
        $this->FirstName = $FirstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->LastName;
    }

    public function setLastName(?string $LastName): self
    {
        $this->LastName = $LastName;

        return $this;
    }

    public function getCNI(): ?string
    {
        return $this->CNI;
    }

    public function setCNI(?string $CNI): self
    {
        $this->CNI = $CNI;

        return $this;
    }

    public function getNumTelephone(): ?int
    {
        return $this->numTelephone;
    }

    public function setNumTelephone(?int $numTelephone): self
    {
        $this->numTelephone = $numTelephone;

        return $this;
    }

    public function getSexe(): ?string
    {
        return $this->sexe;
    }

    public function setSexe(?string $sexe): self
    {
        $this->sexe = $sexe;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return Collection<int, OffreStage>
     */
    public function getOffreStages(): Collection
    {
        return $this->offreStages;
    }

    //🔥usefull when you gonna need to add automaticly a offre to a user🔥
    public function addOffreStage(OffreStage $offreStage): self
    {
        if (!$this->offreStages->contains($offreStage)) {
            $this->offreStages->add($offreStage);
            $offreStage->setAjouterPar($this);
        }

        return $this;
    }

    public function removeOffreStage(OffreStage $offreStage): self
    {
        if ($this->offreStages->removeElement($offreStage)) {
            // set the owning side to null (unless already changed)
            if ($offreStage->getAjouterPar() === $this) {
                $offreStage->setAjouterPar(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, OffreEmploi>
     */
    public function getOffreEmplois(): Collection
    {
        return $this->offreEmplois;
    }

    public function addOffreEmploi(OffreEmploi $offreEmploi): self
    {
        if (!$this->offreEmplois->contains($offreEmploi)) {
            $this->offreEmplois->add($offreEmploi);
            $offreEmploi->setAjouterPar($this);
        }

        return $this;
    }

    public function removeOffreEmploi(OffreEmploi $offreEmploi): self
    {
        if ($this->offreEmplois->removeElement($offreEmploi)) {
            // set the owning side to null (unless already changed)
            if ($offreEmploi->getAjouterPar() === $this) {
                $offreEmploi->setAjouterPar(null);
            }
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
            $formation->setAjouterPar($this);
        }

        return $this;
    }

    public function removeFormation(Formation $formation): self
    {
        if ($this->formations->removeElement($formation)) {
            // set the owning side to null (unless already changed)
            if ($formation->getAjouterPar() === $this) {
                $formation->setAjouterPar(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, cycleEtude>
     */
    public function getPoursuivre(): Collection
    {
        return $this->poursuivre;
    }

    public function addPoursuivre(CycleEtude $poursuivre): self
    {
        if (!$this->poursuivre->contains($poursuivre)) {
            $this->poursuivre->add($poursuivre);
        }

        return $this;
    }

    public function removePoursuivre(CycleEtude $poursuivre): self
    {
        $this->poursuivre->removeElement($poursuivre);

        return $this;
    }



}
