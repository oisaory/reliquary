<?php

namespace App\Entity;

use App\Entity\Relic;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class Image
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private string $filename;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $originalFilename = null;

    #[ORM\Column(length: 255)]
    private string $mimeType;

    #[ORM\Column]
    private int $size;

    #[ORM\ManyToOne(targetEntity: Relic::class, inversedBy: 'images')]
    #[ORM\JoinColumn(onDelete: 'CASCADE')]
    private ?ImageOwnerInterface $owner = null;

    #[ORM\Column(length: 50)]
    private string $ownerType; // 'relic' or 'saint'

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFilename(): string
    {
        return $this->filename;
    }

    public function setFilename(string $filename): self
    {
        $this->filename = $filename;

        return $this;
    }

    public function getOriginalFilename(): ?string
    {
        return $this->originalFilename;
    }

    public function setOriginalFilename(?string $originalFilename): self
    {
        $this->originalFilename = $originalFilename;

        return $this;
    }

    public function getMimeType(): string
    {
        return $this->mimeType;
    }

    public function setMimeType(string $mimeType): self
    {
        $this->mimeType = $mimeType;

        return $this;
    }

    public function getSize(): int
    {
        return $this->size;
    }

    public function setSize(int $size): self
    {
        $this->size = $size;

        return $this;
    }

    public function getOwner(): ?ImageOwnerInterface
    {
        return $this->owner;
    }

    public function setOwner(?ImageOwnerInterface $owner): self
    {
        $this->owner = $owner;

        return $this;
    }

    public function getOwnerType(): string
    {
        return $this->ownerType;
    }

    public function setOwnerType(string $ownerType): self
    {
        $this->ownerType = $ownerType;

        return $this;
    }
}
