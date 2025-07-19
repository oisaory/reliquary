<?php

namespace App\Twig;

use App\Entity\Saint;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class SaintExtension extends AbstractExtension
{
    private TranslatorInterface $translator;
    
    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }
    
    public function getFilters(): array
    {
        return [
            new TwigFilter('format_saint_name', [$this, 'formatSaintName']),
        ];
    }
    
    /**
     * Formats a saint's name with the appropriate title prefix
     */
    public function formatSaintName(Saint $saint): string
    {
        $canonicalStatus = $saint->getCanonicalStatus();
        
        if ($canonicalStatus === null) {
            return $saint->getName() ?? '';
        }
        
        $titleKey = $canonicalStatus->getTitleTransKey();
        $title = $this->translator->trans($titleKey, [], 'saint');
        
        return sprintf('%s %s', $title, $saint->getName());
    }
}