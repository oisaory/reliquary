<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\SecurityBundle\Security;

class LocationResolverService
{
    private OpenStreetMapService $osmService;

    public function __construct(OpenStreetMapService $osmService)
    {
        $this->osmService = $osmService;
    }

    /**
     * Resolve location from various sources in order of priority:
     * 1. Query parameter
     * 2. User's stored location
     * 3. Session data
     *
     * @param Request $request The current request
     * @param Security $security The security service
     * @param string|null $searchQuery Optional search query parameter
     * @return array{location: ?array, available: bool} Location data and availability flag
     */
    public function resolveLocation(Request $request, Security $security, ?string $searchQuery = null): array
    {
        // Try to get location from search query first
        $location = $this->resolveFromQuery($searchQuery);
        if ($location) {
            return [
                'location' => $location,
                'available' => true
            ];
        }

        // Try to get location from authenticated user
        $location = $this->resolveFromUser($security->getUser());
        if ($location) {
            return [
                'location' => $location,
                'available' => true
            ];
        }

        // Try to get location from session
        $location = $this->resolveFromSession($request);
        if ($location) {
            return [
                'location' => $location,
                'available' => true
            ];
        }

        // No location available
        return [
            'location' => null,
            'available' => false
        ];
    }

    /**
     * Resolve location from search query using OpenStreetMap service
     */
    private function resolveFromQuery(?string $searchQuery): ?array
    {
        if (!$searchQuery) {
            return null;
        }

        // Use OpenStreetMapService to geocode the address
        $geocodeResults = $this->osmService->searchAddresses($searchQuery, 1);
        
        // If we got results, use the first one
        if (!empty($geocodeResults)) {
            return [
                'latitude' => (float)$geocodeResults[0]['lat'],
                'longitude' => (float)$geocodeResults[0]['lon']
            ];
        }

        return null;
    }

    /**
     * Resolve location from authenticated user
     */
    private function resolveFromUser(?object $user): ?array
    {
        if ($user && method_exists($user, 'getLatitude') && method_exists($user, 'getLongitude') && 
            $user->getLatitude() && $user->getLongitude()) {
            return [
                'latitude' => $user->getLatitude(),
                'longitude' => $user->getLongitude()
            ];
        }

        return null;
    }

    /**
     * Resolve location from session data
     */
    private function resolveFromSession(Request $request): ?array
    {
        if ($request->getSession()->has('user_geolocation')) {
            $sessionGeo = $request->getSession()->get('user_geolocation');
            if (isset($sessionGeo['latitude']) && isset($sessionGeo['longitude'])) {
                return [
                    'latitude' => $sessionGeo['latitude'],
                    'longitude' => $sessionGeo['longitude']
                ];
            }
        }

        return null;
    }
}