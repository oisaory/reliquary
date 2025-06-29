<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\SecurityBundle\Security;

class GeolocationController extends AbstractController
{
    private Security $security;
    private EntityManagerInterface $entityManager;

    public function __construct(Security $security, EntityManagerInterface $entityManager)
    {
        $this->security = $security;
        $this->entityManager = $entityManager;
    }

    #[Route('/api/geolocation', name: 'api_geolocation_store', methods: ['POST'])]
    public function store(Request $request): JsonResponse
    {
        // Get data from request
        $data = json_decode($request->getContent(), true);

        // Validate input
        if (!isset($data['latitude']) || !isset($data['longitude']) || 
            !is_numeric($data['latitude']) || !is_numeric($data['longitude'])) {
            return $this->json([
                'success' => false,
                'message' => 'Invalid geolocation data. Latitude and longitude are required and must be numeric.'
            ], Response::HTTP_BAD_REQUEST);
        }

        // Get the current user
        $user = $this->security->getUser();

        if (!$user) {
            // For non-authenticated users, still store in session for backward compatibility
            $request->getSession()->set('user_geolocation', [
                'latitude' => (float) $data['latitude'],
                'longitude' => (float) $data['longitude'],
                'timestamp' => new \DateTime()
            ]);
        } else {
            // Store in user entity
            $user->setGeolocation((float) $data['latitude'], (float) $data['longitude']);
            $this->entityManager->persist($user);
            $this->entityManager->flush();
        }

        return $this->json([
            'success' => true,
            'message' => 'Geolocation data stored successfully'
        ]);
    }
}
