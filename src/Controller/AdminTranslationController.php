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
        $translationDir = $this->getParameter('kernel.project_dir') . '/translations';
        $enFiles = glob($translationDir . '/*.en.yaml');

        $missingTranslations = [];
        $identicalTranslations = [];

        foreach ($enFiles as $enFile) {
            // Get the domain name from the filename (e.g., 'home' from 'home.en.yaml')
            $domain = basename($enFile, '.en.yaml');

            // Skip the old messages file if it exists
            if ($domain === 'messages') {
                continue;
            }

            // Construct the path to the corresponding pt_BR file
            $ptBrFile = $translationDir . '/' . $domain . '.pt_BR.yaml';

            if (file_exists($ptBrFile)) {
                // Load translation files for this domain
                $enTranslations = Yaml::parseFile($enFile);
                $ptBrTranslations = Yaml::parseFile($ptBrFile);

                // Find missing and identical translations for this domain
                $result = $this->findMissingTranslations($enTranslations, $ptBrTranslations, '', $domain);

                if (!empty($result['missing'])) {
                    $missingTranslations = array_merge($missingTranslations, $result['missing']);
                }

                if (!empty($result['identical'])) {
                    $identicalTranslations = array_merge($identicalTranslations, $result['identical']);
                }
            } else {
                // The entire pt_BR file is missing for this domain
                $enTranslations = Yaml::parseFile($enFile);
                $flattenedTranslations = $this->flattenTranslations($enTranslations, '', $domain);
                $missingTranslations = array_merge($missingTranslations, $flattenedTranslations);
            }
        }

        return $this->render('admin/translations/missing.html.twig', [
            'missingTranslations' => $missingTranslations,
            'identicalTranslations' => $identicalTranslations,
        ]);
    }

    /**
     * Recursively finds missing translations in the target language compared to the source language
     *
     * @param array $source Source language translations (e.g., English)
     * @param array $target Target language translations (e.g., Portuguese)
     * @param string $prefix Current key prefix for nested keys
     * @param string $domain Translation domain (controller name)
     * @return array Array with 'missing' and 'identical' translations
     */
    private function findMissingTranslations(array $source, array $target, string $prefix = '', string $domain = ''): array
    {
        $missing = [];
        $identical = [];

        foreach ($source as $key => $value) {
            $currentKey = $prefix ? "$prefix.$key" : $key;

            // If domain is provided, prefix the key with the domain
            if ($domain && !str_starts_with($currentKey, $domain . '.')) {
                $currentKey = $domain . '.' . $currentKey;
            }

            if (is_array($value)) {
                // If this is a nested array, recurse into it
                if (!isset($target[$key]) || !is_array($target[$key])) {
                    // The entire section is missing in the target
                    if (is_array($value)) {
                        // Flatten the nested array and add to missing
                        $flattenedValues = $this->flattenTranslations($value, $currentKey);
                        $missing = array_merge($missing, $flattenedValues);
                    } else {
                        $missing[$currentKey] = $value;
                    }
                } else {
                    // Check nested keys
                    $result = $this->findMissingTranslations($value, $target[$key], $currentKey, '');
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

    /**
     * Flattens a nested array of translations into a single-level array with dot notation keys
     *
     * @param array $translations The translations to flatten
     * @param string $prefix Current key prefix for nested keys
     * @param string $domain Translation domain (controller name)
     * @return array Flattened translations
     */
    private function flattenTranslations(array $translations, string $prefix = '', string $domain = ''): array
    {
        $result = [];

        foreach ($translations as $key => $value) {
            $currentKey = $prefix ? "$prefix.$key" : $key;

            // If domain is provided, prefix the key with the domain
            if ($domain && !str_starts_with($currentKey, $domain . '.')) {
                $currentKey = $domain . '.' . $currentKey;
            }

            if (is_array($value)) {
                // Recurse into nested arrays
                $result = array_merge($result, $this->flattenTranslations($value, $currentKey));
            } else {
                // Add leaf node to result
                $result[$currentKey] = $value;
            }
        }

        return $result;
    }
}
