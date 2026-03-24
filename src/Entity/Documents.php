<?php

namespace App\Entity;

use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use ApiPlatform\Metadata\ApiResource;
use App\Repository\DocumentsRepository;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use ApiPlatform\Metadata\Delete;


#[ORM\Entity(repositoryClass: DocumentsRepository::class)]
#[ApiResource(
    normalizationContext: ['groups' => ['doc:read']],
    denormalizationContext: ['groups' => ['doc:write']],
    operations: [
        new Get(),
        new GetCollection(),
        new Post(security: "is_granted('ROLE_ENSEIGNANT')"),
        new Put(security: "is_granted('ROLE_ENSEIGNANT')"),
        new Delete(security: "is_granted('ROLE_ENSEIGNANT')")
    ])]
class Documents
{
    #[Groups(['doc:read'])]
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Groups(['doc:read', 'doc:write'])]
    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[Groups(['doc:read', 'doc:write'])]
    #[ORM\ManyToOne]
    #[Assert\NotBlank]
    private ?Sae $sae = null;

    #[Groups(['doc:read', 'doc:write'])]
    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotBlank]
    private ?Enseignants $enseignant = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getSae(): ?Sae
    {
        return $this->sae;
    }

    public function setSae(?Sae $sae): static
    {
        $this->sae = $sae;

        return $this;
    }

    public function getEnseignant(): ?Enseignants
    {
        return $this->enseignant;
    }

    public function setEnseignant(?Enseignants $enseignant): static
    {
        $this->enseignant = $enseignant;

        return $this;
    }
}
