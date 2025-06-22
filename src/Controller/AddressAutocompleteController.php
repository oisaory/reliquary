<?php

namespace App\Controller;

use App\Service\OpenStreetMapService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AddressAutocompleteController extends AbstractController
{
    private OpenStreetMapService $osmService;
    
    public function __construct(OpenStreetMapService $osmService)
    {
        $this->osmService = $osmService;
    }

    #[Route('/api/address-autocomplete', name: 'api_address_autocomplete')]
    public function autocomplete(Request $request): Response
    {
        $query = $request->query->get('query');
        
        if (empty($query)) {
            return $this->json(['results' => []]);
        }
        
        $results = $this->osmService->searchAddresses($query);
        
        return $this->json(['results' => $results]);
    }
}