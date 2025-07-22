<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class RelicDescriptionAutocompleteType extends AbstractType
{
    private UrlGeneratorInterface $urlGenerator;
    private TranslatorInterface $translator;

    public function __construct(UrlGeneratorInterface $urlGenerator, TranslatorInterface $translator)
    {
        $this->urlGenerator = $urlGenerator;
        $this->translator = $translator;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'placeholder' => $this->translator->trans('relic.form.description_placeholder', [], 'relic'),
            'autocomplete' => true,
            'autocomplete_url' => $this->urlGenerator->generate('api_relic_description_autocomplete'),
            'tom_select_options' => [
                'create' => true,
                'maxItems' => 1,
            ],
            'attr' => [
                'class' => 'autocomplete-input',
                'data-autocomplete-target' => 'input',
                'data-action' => 'autocomplete#change',
            ],
        ]);
    }

    public function getParent(): string
    {
        return TextType::class;
    }
}
