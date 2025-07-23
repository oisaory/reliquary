<?php

namespace App\EventListener;

use App\Entity\Saint;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class SaintFormListener
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function onPreSubmit(FormEvent $event): void
    {
        $data = $event->getData();

        // Check if saint field exists and is not a number
        if (isset($data['saint']) && !is_numeric($data['saint'])) {
            // Create a new Saint entity with the name
            $saint = new Saint();
            $saint->setName($data['saint']);
            $saint->setIsIncomplete(true);

            // Persist and flush the new Saint entity
            $this->entityManager->persist($saint);
            $this->entityManager->flush();

            // Set the new saint's ID as the value for the saint field
            $data['saint'] = $saint->getId();
            $event->setData($data);
        }
    }
}
