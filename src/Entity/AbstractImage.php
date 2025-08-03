<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\MappedSuperclass]
abstract class AbstractImage
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    protected ?int $id = null;

    #[ORM\Column(length: 255)]
    protected string $filename;

    #[ORM\Column(length: 255, nullable: true)]
    protected ?string $originalFilename = null;
    
    #[ORM\Column(length: 255, nullable: true)]
    protected ?string $thumbnailFilename = null;

    #[ORM\Column(length: 255)]
    protected string $mimeType;

    #[ORM\Column]
    protected int $size;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: true)]
    protected ?User $uploader = null;

    // Getters and setters
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

    public function getUploader(): ?User
    {
        return $this->uploader;
    }

    public function setUploader(?User $uploader): self
    {
        $this->uploader = $uploader;
        return $this;
    }

    public function getThumbnailFilename(): ?string
    {
        return $this->thumbnailFilename;
    }

    public function setThumbnailFilename(?string $thumbnailFilename): self
    {
        $this->thumbnailFilename = $thumbnailFilename;
        return $this;
    }
}
