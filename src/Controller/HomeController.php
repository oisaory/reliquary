<?php

namespace App\Controller;

use App\Repository\RelicRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\SecurityBundle\Security;
use Knp\Component\Pager\PaginatorInterface;

/**
 * Controller for the home page that displays relics
 * 
 * When a user has geolocation defined (either authenticated user or guest with session data),
 * the home page will filter relics to show only those within a 45km radius of the user's location.
 * If no geolocation is available, all relics will be displayed.
 */
final class HomeController extends AbstractController
{
    private const DEFAULT_RADIUS_KM = 45;

    #[Route('/', name: 'app_home')]
    public function index(RelicRepository $relicRepository, Request $request, Security $security): Response
    {
        $result = $this->getFilteredRelics($relicRepository, $request, $security);

        return $this->render('home/index.html.twig', [
            'relics' => $result['relics'],
            'radius' => $result['radius'],
            'locationAvailable' => $result['locationAvailable'],
        ]);
    }

    #[Route('/home/desktop', name: 'app_home_relics_desktop', methods: ['GET'])]
    public function homeRelicsDesktop(RelicRepository $relicRepository, Request $request, Security $security, PaginatorInterface $paginator): Response
    {
        $result = $this->getFilteredRelics($relicRepository, $request, $security);

        $pagination = $paginator->paginate(
            $result['relics'],
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('relic/_relic_list_desktop.html.twig', [
            'pagination' => $pagination,
        ]);
    }

    #[Route('/home/mobile', name: 'app_home_relics_mobile', methods: ['GET'])]
    public function homeRelicsMobile(RelicRepository $relicRepository, Request $request, Security $security, PaginatorInterface $paginator): Response
    {
        $result = $this->getFilteredRelics($relicRepository, $request, $security);

        $pagination = $paginator->paginate(
            $result['relics'],
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('relic/_relic_list_mobile.html.twig', [
            'pagination' => $pagination,
        ]);
    }

    private function getFilteredRelics(RelicRepository $relicRepository, Request $request, Security $security): array
    {
        $radius = self::DEFAULT_RADIUS_KM;
        $user = $security->getUser();
        $userLocation = null;
        $locationAvailable = false;

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
                $radius,
                $user // Pass the current user for visibility restrictions
            );
            $locationAvailable = true;
        } else {
            // Fall back to all relics if no geolocation is available
            $relics = $relicRepository->findAllWithVisibility($user); // Use the new method with visibility restrictions
        }

        return [
            'relics' => $relics,
            'radius' => $radius,
            'locationAvailable' => $locationAvailable,
        ];
    }
}
