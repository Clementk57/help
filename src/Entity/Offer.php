<?php
// src/Entity/Offer.php
namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\ApiFilter;
use App\Repository\OfferRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;

#[ApiResource(
    normalizationContext: ['groups' => ['offer:read']],
    denormalizationContext: ['groups' => ['offer:write']]
)]
#[ApiFilter(SearchFilter::class, properties: ['tag' => 'partial', 'city' => 'partial'])]
#[ORM\Entity(repositoryClass: OfferRepository::class)]
class Offer
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['offer:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['offer:read', 'offer:write'])]
    private ?string $titre = null;

    #[ORM\Column(length: 255)]
    #[Groups(['offer:read', 'offer:write'])]
    private ?string $description = null;

    #[ORM\Column(length: 255)]
    #[Groups(['offer:read', 'offer:write'])]
    private ?string $city = null;

    #[ORM\Column(length: 255)]
    #[Groups(['offer:read', 'offer:write'])]
    private ?string $tag = null;

    #[ORM\Column]
    #[Gedmo\Timestampable(on: 'create')]
    #[Groups(['offer:read'])]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(nullable: true)]
    #[Gedmo\Timestampable(on: 'update')]
    #[Groups(['offer:read'])]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\ManyToOne(inversedBy: 'offers')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['offer:read', 'offer:write'])]
    private ?User $recruiter = null;

    #[ORM\OneToMany(targetEntity: Candidacy::class, mappedBy: 'offer')]
    private Collection $candidacies;

    public function __construct()
    {
        $this->candidacies = new ArrayCollection();
    }

    public function getId(): ?int { return $this->id; }
    public function getTitre(): ?string { return $this->titre; }
    public function setTitre(string $titre): static { $this->titre = $titre; return $this; }
    public function getDescription(): ?string { return $this->description; }
    public function setDescription(string $description): static { $this->description = $description; return $this; }
    public function getCity(): ?string { return $this->city; }
    public function setCity(string $city): static { $this->city = $city; return $this; }
    public function getTag(): ?string { return $this->tag; }
    public function setTag(string $tag): static { $this->tag = $tag; return $this; }
    public function getCreatedAt(): ?\DateTimeImmutable { return $this->createdAt; }
    public function getUpdatedAt(): ?\DateTimeImmutable { return $this->updatedAt; }
    public function getRecruiter(): ?User { return $this->recruiter; }
    public function setRecruiter(?User $recruiter): static { $this->recruiter = $recruiter; return $this; }
    public function getCandidacies(): Collection { return $this->candidacies; }
}
