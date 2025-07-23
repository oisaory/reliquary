<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class RelicDescriptionAutocompleteController extends AbstractController
{
    private TranslatorInterface $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    #[Route('/api/relic-description-autocomplete', name: 'api_relic_description_autocomplete')]
    public function autocomplete(Request $request): Response
    {
        $query = $request->query->get('query');

        // Define the predefined options
        $options = [
            'bone_fragment' => $this->translator->trans('relic.description_options.bone_fragment', [], 'relic'),
            'piece_of_cloth' => $this->translator->trans('relic.description_options.piece_of_cloth', [], 'relic'),
            'hair' => $this->translator->trans('relic.description_options.hair', [], 'relic'),
            'tooth' => $this->translator->trans('relic.description_options.tooth', [], 'relic'),
            'blood' => $this->translator->trans('relic.description_options.blood', [], 'relic'),
            'personal_item' => $this->translator->trans('relic.description_options.personal_item', [], 'relic'),
            'clothing' => $this->translator->trans('relic.description_options.clothing', [], 'relic'),
        ];

        $results = [];

        // If query is empty, return all options
        if (empty($query)) {
            foreach ($options as $value => $text) {
                $results[] = [
                    'value' => $text,
                    'text' => $text,
                ];
            }
            return $this->json(['results' => $results]);
        }

        // Filter options based on the query
        foreach ($options as $value => $text) {
            if (stripos($text, $query) !== false || stripos($value, $query) !== false) {
                $results[] = [
                    'value' => $text,
                    'text' => $text,
                ];
            }
        }
        return $this->json(['results' => $results]);
    }
}
