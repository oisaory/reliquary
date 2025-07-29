<?php

namespace App\Service;

/**
 * Service for analyzing templates to identify untranslated strings
 */
class TranslationAnalyzerService
{
    private string $projectDir;
    private array $skipPatterns;

    /**
     * Constructor
     *
     * @param string $projectDir The project directory path
     */
    public function __construct(string $projectDir)
    {
        $this->projectDir = $projectDir;
        $this->skipPatterns = [
            '/^[0-9\s\.\,\:\;\-\_\+\=\(\)\[\]\{\}\*\&\^\%\$\#\@\!\?\<\>\/\\\|]+$/', // Just symbols and numbers
            '/^https?:\/\//', // URLs
            '/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/' // Email addresses
        ];
    }

    /**
     * Scans a directory for Twig templates and identifies untranslated strings
     *
     * @param string|null $directory Directory to scan (relative to templates directory)
     * @return array Array of untranslated strings grouped by file
     */
    public function scanDirectory(?string $directory = null): array
    {
        $templatesDir = $this->projectDir . '/templates';
        $scanDir = $directory ? $templatesDir . '/' . $directory : $templatesDir;
        
        $results = [];
        $this->scanDirectoryRecursive($scanDir, $results);
        
        return $results;
    }

    /**
     * Recursively scans a directory for Twig templates
     *
     * @param string $dir Directory to scan
     * @param array &$results Results array to populate
     */
    private function scanDirectoryRecursive(string $dir, array &$results): void
    {
        $files = scandir($dir);
        
        foreach ($files as $file) {
            if ($file === '.' || $file === '..') {
                continue;
            }
            
            $path = $dir . '/' . $file;
            
            if (is_dir($path)) {
                $this->scanDirectoryRecursive($path, $results);
            } elseif (pathinfo($path, PATHINFO_EXTENSION) === 'twig') {
                $fileResults = $this->scanFile($path);
                
                if (!empty($fileResults)) {
                    $relativePath = str_replace($this->projectDir . '/', '', $path);
                    $results[$relativePath] = $fileResults;
                }
            }
        }
    }

    /**
     * Scans a single file for untranslated strings
     *
     * @param string $filePath Path to the file
     * @return array Array of untranslated strings with suggested translations
     */
    public function scanFile(string $filePath): array
    {
        $content = file_get_contents($filePath);
        $results = [];
        
        // Skip if file can't be read
        if ($content === false) {
            return $results;
        }
        
        // Regular expression patterns to find potential untranslated strings
        $patterns = [
            // Match text between HTML tags that isn't already using trans filter
            '/>([^<>{}|]+?)</s',
            
            // Match alt attributes that aren't using trans
            '/alt="([^"{}|]+?)"/s',
            
            // Match title attributes that aren't using trans
            '/title="([^"{}|]+?)"/s',
            
            // Match placeholder attributes that aren't using trans
            '/placeholder="([^"{}|]+?)"/s',
            
            // Match text in button elements
            '/<button[^>]*>([^<>{}|]+?)<\/button>/s',
        ];
        
        foreach ($patterns as $pattern) {
            if (preg_match_all($pattern, $content, $matches)) {
                foreach ($matches[1] as $match) {
                    $trimmed = trim($match);
                    
                    // Skip if empty, just numbers, or very short
                    if (empty($trimmed) || is_numeric($trimmed) || strlen($trimmed) < 3) {
                        continue;
                    }
                    
                    // Skip if it looks like a variable or already has trans
                    if (strpos($trimmed, '{{') !== false || 
                        strpos($trimmed, '|trans') !== false ||
                        strpos($trimmed, '{%') !== false) {
                        continue;
                    }
                    
                    // Skip common non-translatable content
                    $skip = false;
                    foreach ($this->skipPatterns as $skipPattern) {
                        if (preg_match($skipPattern, $trimmed)) {
                            $skip = true;
                            break;
                        }
                    }
                    
                    if (!$skip) {
                        // Generate suggested translation key and domain
                        $domain = $this->determineDomain($filePath);
                        $suggestedKey = $this->generateTranslationKey($trimmed);
                        
                        $results[] = [
                            'text' => $trimmed,
                            'suggested_key' => $suggestedKey,
                            'domain' => $domain,
                            'full_key' => "$domain.$suggestedKey",
                            'translation_code' => "{{ '$domain.$suggestedKey'|trans({}, '$domain') }}"
                        ];
                    }
                }
            }
        }
        
        // Remove duplicates
        $uniqueResults = [];
        foreach ($results as $result) {
            $uniqueResults[$result['text']] = $result;
        }
        
        return array_values($uniqueResults);
    }

    /**
     * Determines the appropriate translation domain based on the file path
     *
     * @param string $filePath Path to the file
     * @return string Translation domain
     */
    public function determineDomain(string $filePath): string
    {
        $relativePath = str_replace($this->projectDir . '/templates/', '', $filePath);
        $parts = explode('/', $relativePath);
        
        // Default domain is 'common'
        $domain = 'common';
        
        // If the file is in a subdirectory, use that as the domain
        if (count($parts) > 0 && $parts[0] !== '') {
            $domain = $parts[0];
        }
        
        return $domain;
    }

    /**
     * Generates a suggested translation key based on the content
     *
     * @param string $text The text to generate a key for
     * @return string Suggested translation key
     */
    public function generateTranslationKey(string $text): string
    {
        // Convert to lowercase
        $key = strtolower($text);
        
        // Replace special characters with dots
        $key = preg_replace('/[^a-z0-9]+/', '.', $key);
        
        // Trim dots from beginning and end
        $key = trim($key, '.');
        
        // Limit length
        if (strlen($key) > 30) {
            $key = substr($key, 0, 30);
            // Ensure we don't end with a dot
            $key = rtrim($key, '.');
        }
        
        return $key;
    }

    /**
     * Gets all untranslated strings from all templates
     *
     * @return array Array with total count and untranslated strings grouped by file
     */
    public function getAllUntranslatedStrings(): array
    {
        $untranslatedStrings = $this->scanDirectory();
        $totalCount = 0;
        
        foreach ($untranslatedStrings as $file => $strings) {
            $totalCount += count($strings);
        }
        
        return [
            'total_count' => $totalCount,
            'total_files' => count($untranslatedStrings),
            'untranslated_strings' => $untranslatedStrings
        ];
    }
}