<?php
// src/Entity/Candidacy.php
namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\CandidacyRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ApiResource(
    normalizationContext: ['groups' => ['candidacy:read']],
    denormalizationContext: ['groups' => ['candidacy:write']]
)]
#[ORM\Entity(repositoryClass: CandidacyRepository::class)]
class Candidacy
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups('candidacy:read')]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['candidacy:read', 'candidacy:write'])]
    private ?string $message = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['candidacy:read', 'candidacy:write'])]
    private ?string $file = null;

    #[ORM\ManyToOne(inversedBy: 'candidacies')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['candidacy:read', 'candidacy:write'])]
    private ?Offer $offer = null;

    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'candidacies')]
    #[Groups(['candidacy:read', 'candidacy:write'])]
    private Collection $candidate;

    public function __construct()
    {
        $this->candidate = new ArrayCollection();
    }

    public function getId(): ?int { return $this->id; }
    public function getMessage(): ?string { return $this->message; }
    public function setMessage(string $message): static { $this->message = $message; return $this; }
    public function getFile(): ?string { return $this->file; }
    public function setFile(?string $file): static { $this->file = $file; return $this; }
    public function getOffer(): ?Offer { return $this->offer; }
    public function setOffer(?Offer $offer): static { $this->offer = $offer; return $this; }
    public function getCandidate(): Collection { return $this->candidate; }
    public function addCandidate(User $candidate): static
    {
        if (!$this->candidate->contains($candidate)) {
            $this->candidate->add($candidate);
        }
        return $this;
    }
    public function removeCandidate(User $candidate): static
    {
        $this->candidate->removeElement($candidate);
        return $this;
    }
}
