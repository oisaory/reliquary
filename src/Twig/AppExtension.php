<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\Extension\GlobalsInterface;

class AppExtension extends AbstractExtension implements GlobalsInterface
{
    private string $appVersion;

    public function __construct(string $appVersion)
    {
        $this->appVersion = $appVersion;
    }

    public function getGlobals(): array
    {
        return [
            'app_version' => $this->appVersion,
        ];
    }
}