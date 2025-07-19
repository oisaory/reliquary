<?php

namespace App\EventListener;

use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * Event listener that sets the locale from the session for each request
 */
#[AsEventListener(event: KernelEvents::REQUEST, priority: 20)]
final class LocaleListener
{
    /**
     * Sets the locale from the session for each request
     * 
     * @param RequestEvent $event The request event
     */
    public function __invoke(RequestEvent $event): void
    {
        $request = $event->getRequest();
        
        // Skip non-master requests (e.g., sub-requests)
        if (!$event->isMainRequest()) {
            return;
        }
        
        // Try to get the locale from the session
        if ($request->hasSession() && $locale = $request->getSession()->get('_locale')) {
            $request->setLocale($locale);
        }
    }
}