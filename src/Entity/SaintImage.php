<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class SaintImage extends AbstractImage
{
    #[ORM\ManyToOne(targetEntity: Saint::class, inversedBy: 'images')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private Saint $saint;

    public function getSaint(): Saint
    {
        return $this->saint;
    }

    public function setSaint(Saint $saint): self
    {
        $this->saint = $saint;
        return $this;
    }
}