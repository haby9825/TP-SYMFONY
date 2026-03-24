<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Enum\Type;
use App\Repository\NotificationsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use ApiPlatform\Metadata\Delete;

#[ORM\Entity(repositoryClass: NotificationsRepository::class)]
#[ApiResource(
    normalizationContext: ['groups' => ['notif:read']],
    denormalizationContext: ['groups' => ['notif:write']],
    operations: [
        new Get(),
        new GetCollection(),
        new Post(security: "is_granted('ROLE_ENSEIGNANT')"),
        new Put(security: "is_granted('ROLE_ENSEIGNANT')"),
        new Delete(security: "is_granted('ROLE_ENSEIGNANT')")
    ])]
class Notifications
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Groups(['notif:read', 'notif:write'])]
    #[ORM\Column(length: 255)]
    private ?string $titre = null;

    #[Groups(['notif:read', 'notif:write'])]
    #[ORM\Column(type: Types::TEXT)]
    private ?string $contenu = null;

    #[Groups(['notif:read', 'notif:write'])]
    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTime $date = null;

    #[Groups(['notif:read', 'notif:write'])]
    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?sae $sae = null;

    #[Groups(['notif:read', 'notif:write'])]
    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Enseignants $enseignant = null;

    /**
     * @var Collection<int, Etudiants>
     */
    #[Groups(['notif:read', 'notif:write'])]
    #[ORM\ManyToMany(targetEntity: Etudiants::class)]
    private Collection $etudiant;

    #[Groups(['notif:read', 'notif:write'])]
    #[ORM\Column]
    private ?bool $status = null;

    #[ORM\Column(enumType: Type::class)]
    private ?Type $Type = null;

    public function __construct()
    {
        $this->etudiant = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): static
    {
        $this->titre = $titre;

        return $this;
    }

    public function getContenu(): ?string
    {
        return $this->contenu;
    }

    public function setContenu(string $contenu): static
    {
        $this->contenu = $contenu;

        return $this;
    }

    public function getType(): Type
    {
        return $this->type;
    }

    public function setType(Type $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getDate(): ?\DateTime
    {
        return $this->date;
    }

    public function setDate(\DateTime $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getSae(): ?sae
    {
        return $this->sae;
    }

    public function setSae(?sae $sae): static
    {
        $this->sae = $sae;

        return $this;
    }

    public function getEnseignant(): ?Enseignants
    {
        return $this->enseignant;
    }

    public function setEnseignant(Enseignants $enseignant): static
    {
        $this->enseignant = $enseignant;

        return $this;
    }

    /**
     * @return Collection<int, Etudiants>
     */
    public function getEtudiant(): Collection
    {
        return $this->etudiant;
    }

    public function addEtudiant(Etudiants $etudiant): static
    {
        if (!$this->etudiant->contains($etudiant)) {
            $this->etudiant->add($etudiant);
        }

        return $this;
    }

    public function removeEtudiant(Etudiants $etudiant): static
    {
        $this->etudiant->removeElement($etudiant);

        return $this;
    }

    public function isStatus(): ?bool
    {
        return $this->status;
    }

    public function setStatus(bool $status): static
    {
        $this->status = $status;

        return $this;
    }
}
