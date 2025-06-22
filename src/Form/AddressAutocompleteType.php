<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class AddressAutocompleteType extends AbstractType
{
    private UrlGeneratorInterface $urlGenerator;

    public function __construct(UrlGeneratorInterface $urlGenerator)
    {
        $this->urlGenerator = $urlGenerator;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'placeholder' => 'Start typing to search for an address',
            'autocomplete' => true,
            'autocomplete_url' => $this->urlGenerator->generate('api_address_autocomplete'),
            'tom_select_options' => [
                'create' => false,
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
