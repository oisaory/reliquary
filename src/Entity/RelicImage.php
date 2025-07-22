<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class RelicImage extends AbstractImage
{
    #[ORM\ManyToOne(targetEntity: Relic::class, inversedBy: 'images')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private Relic $relic;

    public function getRelic(): Relic
    {
        return $this->relic;
    }

    public function setRelic(Relic $relic): self
    {
        $this->relic = $relic;
        return $this;
    }
}