<?php

namespace App\Entity;

use App\Repository\EtudiantsRepository;
use Doctrine\ORM\Mapping as ORM;
use App\Enum\Promotion;
use App\Enum\Specialite;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use ApiPlatform\Metadata\Delete;


#[ORM\Entity(repositoryClass: EtudiantsRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_NUM_ETUDIANT', fields: ['numEtudiant'])]
#[ApiResource(
    normalizationContext: ['groups' => ['etudiant:read']],
    denormalizationContext: ['groups' => ['etudiant:write']],
        operations: [
        new Get(),
        new GetCollection(),
        new Post(security: "is_granted('ROLE_ADMIN')"),
        new Put(security: "is_granted('ROLE_ADMIN')"),
        new Delete(security: "is_granted('ROLE_ADMIN')")
    ]
)]
class Etudiants implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[Groups(['etudiant:read'])]
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Groups(['etudiant:read', 'etudiant:write'])]
    #[ORM\Column(type: 'integer')]
    #[Assert\NotBlank]
    #[Assert\Range(min: 10000000, max: 99999999, notInRangeMessage: 'Le numéro étudiant doit contenir 8 chiffres')]
    private ?int $numEtudiant = null;

    /**
     * @var list<string> The user roles
     */
    #[Groups(['etudiant:read'])]
    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    #[Assert\NotBlank]
    private ?string $password = null;

    #[Groups(['etudiant:read', 'etudiant:write'])]
    #[ORM\Column(length: 30)]
    #[Assert\NotBlank]
    private ?string $nom = null;

    #[Groups(['etudiant:read', 'etudiant:write'])]
    #[ORM\Column(length: 30)]
    #[Assert\NotBlank]
    private ?string $prenom = null;

    #[Groups(['etudiant:read', 'etudiant:write'])]
    #[ORM\Column(enumType: Promotion::class)]
    #[Assert\NotBlank]
    private ?Promotion $promotion = null;

    #[Groups(['etudiant:read', 'etudiant:write'])]
    #[ORM\Column(enumType: Specialite::class, nullable: true)]
    private ?Specialite $specialite = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumEtudiant(): ?int
    {
        return $this->numEtudiant;
    }

    public function setNumEtudiant(int $numEtudiant): static
    {
        $this->numEtudiant = $numEtudiant;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->numEtudiant;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Ensure the session doesn't contain actual password hashes by CRC32C-hashing them, as supported since Symfony 7.3.
     */
    public function __serialize(): array
    {
        $data = (array) $this;
        $data["\0" . self::class . "\0password"] = hash('crc32c', $this->password);

        return $data;
    }

    #[\Deprecated]
    public function eraseCredentials(): void
    {
        // @deprecated, to be removed when upgrading to Symfony 8
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

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): static
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getPromotion(): Promotion
    {
        return $this->promotion;
    }

    public function setPromotion(Promotion $promotion): static
    {
        $this->promotion = $promotion;

        return $this;
    }

    public function getSpecialite(): Specialite
    {
        return $this->specialite;
    }

    public function setSpecialite(Specialite $specialite): static
    {
        $this->specialite = $specialite;

        return $this;
    }
}
