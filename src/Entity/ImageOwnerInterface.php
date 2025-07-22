<?php

namespace App\Entity;

use Doctrine\Common\Collections\Collection;

interface ImageOwnerInterface
{
    public function getId(): ?int;
    public function getImages(): Collection;
    public function addImage(AbstractImage $image): self;
    public function removeImage(AbstractImage $image): self;
}
