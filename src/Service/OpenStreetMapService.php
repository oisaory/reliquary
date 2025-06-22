<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class OpenStreetMapService
{
    private HttpClientInterface $httpClient;
    
    public function __construct(HttpClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
    }
    
    /**
     * Search for addresses using OpenStreetMap Nominatim API
     */
    public function searchAddresses(string $query, int $limit = 5): array
    {
        $response = $this->httpClient->request('GET', 'https://nominatim.openstreetmap.org/search', [
            'query' => [
                'q' => $query,
                'format' => 'json',
                'addressdetails' => 1,
                'limit' => $limit,
            ],
            'headers' => [
                'User-Agent' => 'ReliquaryApp/1.0',  // OSM requires a user agent
            ],
        ]);
        
        $results = json_decode($response->getContent(), true);
        
        // Format results for autocomplete
        $formattedResults = [];
        foreach ($results as $result) {
            $formattedResults[] = [
                'value' => $result['display_name'],  // Use the display name as the value
                'text' => $result['display_name'],   // Display name as the text
                // Include additional data like coordinates
                'lat' => $result['lat'],
                'lon' => $result['lon'],
                // Store the full address details for potential future use
                'details' => $result['address'] ?? [],
            ];
        }
        
        return $formattedResults;
    }
}