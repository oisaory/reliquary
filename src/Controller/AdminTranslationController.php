<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Yaml\Yaml;

/**
 * Controller for managing translations in the admin area
 */
#[Route('/admin/translations')]
#[IsGranted('ROLE_ADMIN')]
class AdminTranslationController extends AbstractController
{
    /**
     * Shows missing translations compared to the English version
     */
    #[Route('/missing', name: 'app_admin_translations_missing')]
    public function missingTranslations(): Response
    {
        // Load translation files
        $enTranslations = Yaml::parseFile($this->getParameter('kernel.project_dir') . '/translations/messages.en.yaml');
        $ptBrTranslations = Yaml::parseFile($this->getParameter('kernel.project_dir') . '/translations/messages.pt_BR.yaml');

        // Find missing and identical translations
        $result = $this->findMissingTranslations($enTranslations, $ptBrTranslations);

        return $this->render('admin/translations/missing.html.twig', [
            'missingTranslations' => $result['missing'],
            'identicalTranslations' => $result['identical'],
        ]);
    }

    /**
     * Recursively finds missing translations in the target language compared to the source language
     *
     * @param array $source Source language translations (e.g., English)
     * @param array $target Target language translations (e.g., Portuguese)
     * @param string $prefix Current key prefix for nested keys
     * @return array Array with 'missing' and 'identical' translations
     */
    private function findMissingTranslations(array $source, array $target, string $prefix = ''): array
    {
        $missing = [];
        $identical = [];

        foreach ($source as $key => $value) {
            $currentKey = $prefix ? "$prefix.$key" : $key;

            if (is_array($value)) {
                // If this is a nested array, recurse into it
                if (!isset($target[$key]) || !is_array($target[$key])) {
                    // The entire section is missing in the target
                    $missing[$currentKey] = $value;
                } else {
                    // Check nested keys
                    $result = $this->findMissingTranslations($value, $target[$key], $currentKey);
                    if (!empty($result['missing'])) {
                        $missing = array_merge($missing, $result['missing']);
                    }
                    if (!empty($result['identical'])) {
                        $identical = array_merge($identical, $result['identical']);
                    }
                }
            } else {
                // This is a leaf node (actual translation string)
                if (!isset($target[$key])) {
                    // Translation key is missing in target
                    $missing[$currentKey] = $value;
                } elseif ($target[$key] === $value) {
                    // Translation exists but is identical to English (likely a stub)
                    $identical[$currentKey] = $value;
                }
            }
        }

        return [
            'missing' => $missing,
            'identical' => $identical
        ];
    }
}
