<?php

namespace App\Controller;

use App\Repository\RelicRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(RelicRepository $relicRepository): Response
    {
        // Fetch all relics as a placeholder for "relics within 45km"
        // In the future, this would use geolocation to filter by distance
        $relics = $relicRepository->findAll();

        return $this->render('home/index.html.twig', [
            'relics' => $relics,
            'radius' => 45, // 45km radius
        ]);
    }
}
