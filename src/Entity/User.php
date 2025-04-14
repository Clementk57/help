<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ApiResource(
    normalizationContext: ['groups' => ['user:read']],
    denormalizationContext: ['groups' => ['user:write']]
)]
#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['user:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    #[Groups(['user:read', 'user:write'])]
    #[Assert\NotBlank(message: "L'email ne peut pas être vide.")]
    #[Assert\Email(message: "L'email n'est pas valide.")]
    private ?string $email = null;

    // Ajouté au groupe de lecture afin d'avoir accès au password haché
    #[ORM\Column]
    #[Groups(['user:read', 'user:write'])]
    private ?string $password = null;

    // Ce champ est utilisé uniquement lors de l'écriture pour envoyer le mot de passe en clair
    #[Groups(['user:write'])]
    #[Assert\NotBlank(groups: ['user:create'], message: "Le mot de passe ne peut pas être vide.")]
    #[Assert\Length(min: 6, groups: ['user:create'], minMessage: "Le mot de passe doit contenir au moins {{ limit }} caractères.")]
    private ?string $plainPassword = null;

    #[ORM\Column]
    private array $roles = [];

    public function __construct()
    {
        // Initialisation éventuelle, par exemple pour les collections ou autres propriétés
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;
        return $this;
    }

    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    public function getRoles(): array
    {
        // Ajoute toujours le rôle ROLE_USER
        return array_unique([...$this->roles, 'ROLE_USER']);
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;
        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;
        return $this;
    }

    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    public function setPlainPassword(?string $plainPassword): static
    {
        $this->plainPassword = $plainPassword;
        return $this;
    }

    public function eraseCredentials(): void
    {
        // On efface le plainPassword pour éviter de le conserver en mémoire
        $this->plainPassword = null;
    }
}
