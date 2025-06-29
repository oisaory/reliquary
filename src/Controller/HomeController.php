<?php

namespace App\Controller;

use App\Repository\RelicRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\SecurityBundle\Security;

/**
 * Controller for the home page that displays relics
 * 
 * When a user has geolocation defined (either authenticated user or guest with session data),
 * the home page will filter relics to show only those within a 45km radius of the user's location.
 * If no geolocation is available, all relics will be displayed.
 */
final class HomeController extends AbstractController
{
    /**
     * Default radius in kilometers for filtering relics by distance
     */
    private const DEFAULT_RADIUS_KM = 45;

    /**
     * Renders the home page with relics filtered by geolocation if available
     * 
     * @param RelicRepository $relicRepository Repository for accessing relic data
     * @param Request $request The current request
     * @param Security $security The security service for accessing the current user
     * @return Response The rendered home page
     */
    #[Route('/', name: 'app_home')]
    public function index(RelicRepository $relicRepository, Request $request, Security $security): Response
    {
        $radius = self::DEFAULT_RADIUS_KM;
        $user = $security->getUser();
        $userLocation = null;

        // Check if user is authenticated and has geolocation
        if ($user && $user->getLatitude() && $user->getLongitude()) {
            $userLocation = [
                'latitude' => $user->getLatitude(),
                'longitude' => $user->getLongitude()
            ];
        } 
        // Check if guest user has geolocation in session
        elseif ($request->getSession()->has('user_geolocation')) {
            $sessionGeo = $request->getSession()->get('user_geolocation');
            if (isset($sessionGeo['latitude']) && isset($sessionGeo['longitude'])) {
                $userLocation = [
                    'latitude' => $sessionGeo['latitude'],
                    'longitude' => $sessionGeo['longitude']
                ];
            }
        }

        // Filter relics by geolocation if available
        if ($userLocation) {
            $relics = $relicRepository->findWithinRadius(
                $userLocation['latitude'],
                $userLocation['longitude'],
                $radius
            );
            $locationAvailable = true;
        } else {
            // Fall back to all relics if no geolocation is available
            $relics = $relicRepository->findAll();
            $locationAvailable = false;
        }

        return $this->render('home/index.html.twig', [
            'relics' => $relics,
            'radius' => $radius,
            'locationAvailable' => $locationAvailable,
        ]);
    }
}
