<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/**
 * Controller for handling locale switching
 */
final class LocaleController extends AbstractController
{
    /**
     * Changes the locale and redirects back to the previous page
     * 
     * @param string $locale The locale to switch to
     * @param Request $request The current request
     * @return Response A redirect response
     */
    #[Route('/change-locale/{locale}', name: 'app_change_locale')]
    public function changeLocale(string $locale, Request $request): Response
    {
        // Validate locale
        if (!in_array($locale, ['en', 'es', 'fr'])) {
            $locale = 'en'; // Default to English if invalid locale
        }
        
        // Store the locale in the session
        $request->getSession()->set('_locale', $locale);
        
        // Get the referer URL or default to homepage
        $referer = $request->headers->get('referer');
        if (!$referer) {
            return $this->redirectToRoute('app_home');
        }
        
        // Redirect back to the previous page
        return $this->redirect($referer);
    }
}