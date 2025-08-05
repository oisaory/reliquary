<?php

namespace App\Controller;

use App\Service\TranslationAnalyzerService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Yaml\Yaml;

#[Route('/admin/translations')]
#[IsGranted('ROLE_ADMIN')]
class AdminTranslationController extends AbstractController
{
    private TranslationAnalyzerService $translationAnalyzer;
    
    public function __construct(TranslationAnalyzerService $translationAnalyzer)
    {
        $this->translationAnalyzer = $translationAnalyzer;
    }
    #[Route('/missing', name: 'app_admin_translations_missing')]
    public function missingTranslations(): Response
    {
        $translationDir = $this->getParameter('kernel.project_dir') . '/translations';
        $enFiles = glob($translationDir . '/*.en.yaml');

        $missingTranslations = [];
        $identicalTranslations = [];

        foreach ($enFiles as $enFile) {
            $domain = basename($enFile, '.en.yaml');

            if ($domain === 'messages') {
                continue;
            }

            $ptBrFile = $translationDir . '/' . $domain . '.pt_BR.yaml';

            if (file_exists($ptBrFile)) {
                $enTranslations = Yaml::parseFile($enFile);
                $ptBrTranslations = Yaml::parseFile($ptBrFile);

                $flattenedSource = $this->flattenTranslations($enTranslations, '', $domain);
                $flattenedTarget = $this->flattenTranslations($ptBrTranslations, '', $domain);
                
                $missing = $this->findMissingTranslationsOnly($flattenedSource, $flattenedTarget);
                if (!empty($missing)) {
                    $missingTranslations = array_merge($missingTranslations, $missing);
                }
                
                $identical = $this->findIdenticalTranslations($flattenedSource, $flattenedTarget);
                if (!empty($identical)) {
                    $identicalTranslations = array_merge($identicalTranslations, $identical);
                }
            } else {
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

    
    private function findMissingTranslationsOnly(array $flattenedSource, array $flattenedTarget): array
    {
        $missing = [];
        foreach ($flattenedSource as $key => $value) {
            if (!array_key_exists($key, $flattenedTarget)) {
                $missing[$key] = $value;
            }
        }
        
        return $missing;
    }
    
    private function findIdenticalTranslations(array $flattenedSource, array $flattenedTarget): array
    {
        $identical = [];
        $exceptionValues = [
            'Admin',
            'Português (Brasil)',
            'Logs',
            'Email',
            'Reliquary',
            'Reliquary Logo',
            'Status'
        ];
        
        foreach ($flattenedSource as $key => $value) {
            if (array_key_exists($key, $flattenedTarget) && $flattenedTarget[$key] === $value) {
                // Skip if the value is in the exception list (ignore keys)
                if (in_array($value, $exceptionValues)) {
                    continue;
                }
                $identical[$key] = $value;
            }
        }
        
        return $identical;
    }

    private function flattenTranslations(array $translations, string $prefix = '', string $domain = ''): array
    {
        $result = [];

        foreach ($translations as $key => $value) {
            $currentKey = $prefix ? "$prefix.$key" : $key;

            if ($domain && !str_starts_with($currentKey, $domain . '.')) {
                $currentKey = $domain . '.' . $currentKey;
            }

            if (is_array($value)) {
                $result = array_merge($result, $this->flattenTranslations($value, $currentKey));
            } else {
                $result[$currentKey] = $value;
            }
        }

        return $result;
    }
    
    #[Route('/scan', name: 'app_admin_translations_scan')]
    public function scanTemplates(Request $request): Response
    {
        $directory = $request->query->get('directory');
        
        if ($directory) {
            $results = $this->translationAnalyzer->scanDirectory($directory);
            $title = sprintf('Untranslated Strings in "%s" Directory', $directory);
        } else {
            $results = $this->translationAnalyzer->getAllUntranslatedStrings();
            $title = 'All Untranslated Strings';
        }
        
        return $this->render('admin/translations/scan.html.twig', [
            'title' => $title,
            'results' => $results,
            'directory' => $directory,
        ]);
    }
}
