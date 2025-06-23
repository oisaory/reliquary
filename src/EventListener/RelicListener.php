<?php

namespace App\EventListener;

use App\Entity\Relic;
use App\Entity\User;
use App\Service\OpenStreetMapService;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Event\PrePersistEventArgs;
use Doctrine\ORM\Events;
use Symfony\Bundle\SecurityBundle\Security;

#[AsEntityListener(event: Events::prePersist, method: 'prePersist', entity: Relic::class)]
final class RelicListener
{
    public function __construct(
        private readonly Security $security,
        private readonly OpenStreetMapService $osmService
    ) {}

    public function prePersist(Relic $relic, PrePersistEventArgs $event): void
    {
        // Set the creator if not already set and a user is authenticated
        if ($relic->getCreator() === null) {
            $user = $this->security->getUser();
            if ($user) {
                $relic->setCreator($user);
            }
        }

        // Set geolocation data if address is provided
        $address = $relic->getAddress();
        if ($address) {
            // Search for the address to get geolocation data
            $results = $this->osmService->searchAddresses($address, 1);

            // If we found a result, set the latitude and longitude
            if (!empty($results)) {
                $result = $results[0];
                $relic->setLatitude((float) $result['lat']);
                $relic->setLongitude((float) $result['lon']);
            }
        }
    }
}
