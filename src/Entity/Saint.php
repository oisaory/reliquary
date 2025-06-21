<?php

namespace App\Entity;

use App\Repository\SaintRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SaintRepository::class)]
class Saint
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $url = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $file = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $canonical_status = null;

    #[ORM\Column(type: "date", nullable: true)]
    private ?\DateTimeInterface $canonization_date = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $canonizing_pope = null;

    #[ORM\Column(type: "text", nullable: true)]
    private ?string $saint_phrase = null;

    #[ORM\Column(type: "text", nullable: true)]
    private ?string $abstract = null;

    #[ORM\Column(type: "text", nullable: true)]
    private ?string $biography = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $image_link = null;

    /**
     * @var Collection<int, Relic>
     */
    #[ORM\OneToMany(targetEntity: Relic::class, mappedBy: 'saint')]
    private Collection $relics;

    public function __construct()
    {
        $this->relics = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, Relic>
     */
    public function getRelics(): Collection
    {
        return $this->relics;
    }

    public function addRelic(Relic $relic): static
    {
        if (!$this->relics->contains($relic)) {
            $this->relics->add($relic);
            $relic->setSaint($this);
        }

        return $this;
    }

    public function removeRelic(Relic $relic): static
    {
        if ($this->relics->removeElement($relic)) {
            // set the owning side to null (unless already changed)
            if ($relic->getSaint() === $this) {
                $relic->setSaint(null);
            }
        }

        return $this;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(?string $url): static
    {
        $this->url = $url;

        return $this;
    }

    public function getFile(): ?string
    {
        return $this->file;
    }

    public function setFile(?string $file): static
    {
        $this->file = $file;

        return $this;
    }

    public function getCanonicalStatus(): ?string
    {
        return $this->canonical_status;
    }

    public function setCanonicalStatus(?string $canonical_status): static
    {
        $this->canonical_status = $canonical_status;

        return $this;
    }

    public function getCanonizationDate(): ?\DateTimeInterface
    {
        return $this->canonization_date;
    }

    public function setCanonizationDate(?\DateTimeInterface $canonization_date): static
    {
        $this->canonization_date = $canonization_date;

        return $this;
    }

    public function getCanonizingPope(): ?string
    {
        return $this->canonizing_pope;
    }

    public function setCanonizingPope(?string $canonizing_pope): static
    {
        $this->canonizing_pope = $canonizing_pope;

        return $this;
    }

    public function getSaintPhrase(): ?string
    {
        return $this->saint_phrase;
    }

    public function setSaintPhrase(?string $saint_phrase): static
    {
        $this->saint_phrase = $saint_phrase;

        return $this;
    }

    public function getAbstract(): ?string
    {
        return $this->abstract;
    }

    public function setAbstract(?string $abstract): static
    {
        $this->abstract = $abstract;

        return $this;
    }

    public function getBiography(): ?string
    {
        return $this->biography;
    }

    public function setBiography(?string $biography): static
    {
        $this->biography = $biography;

        return $this;
    }

    public function getImageLink(): ?string
    {
        return $this->image_link;
    }

    public function setImageLink(?string $image_link): static
    {
        $this->image_link = $image_link;

        return $this;
    }

    public function __toString(): string
    {
        return $this->name ?? '';
    }
}
