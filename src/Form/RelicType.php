<?php

namespace App\Form;

use App\Entity\Relic;
use App\Entity\Saint;
use App\Enum\RelicDegree;
use App\Form\SaintAutocompleteField;
use App\Form\AddressAutocompleteType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Contracts\Translation\TranslatorInterface;

class RelicType extends AbstractType
{
    private TranslatorInterface $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('address', AddressAutocompleteType::class, [
                'label' => 'relic.form.address',
                'translation_domain' => 'relic',
                'help' => 'The general address where this relic is located',
                'help_attr' => ['class' => 'form-text text-muted'],
                'label_attr' => ['class' => 'form-label'],
            ])
            ->add('location', null, [
                'label' => 'relic.form.location',
                'translation_domain' => 'relic',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Enter the specific location within the address',
                ],
                'help' => 'Where specifically within the address is this relic located? (e.g., "North Chapel", "Main Altar", etc.)',
                'help_attr' => ['class' => 'form-text text-muted'],
                'label_attr' => ['class' => 'form-label'],
            ])
            ->add('saint', SaintAutocompleteField::class, [
                'label' => 'relic.form.saint',
                'translation_domain' => 'relic',
                'help' => 'Select the saint associated with this relic',
                'help_attr' => ['class' => 'form-text text-muted'],
                'label_attr' => ['class' => 'form-label'],
            ])
            ->add('degree', EnumType::class, [
                'label' => 'relic.form.degree',
                'translation_domain' => 'relic',
                'class' => RelicDegree::class,
                'choice_label' => function (RelicDegree $degree) {
                    return $this->translator->trans($degree->getTitleTransKey(), [], 'relic');
                },
                'help' => 'relic.form.degree_help',
                'help_attr' => ['class' => 'form-text text-muted'],
                'label_attr' => ['class' => 'form-label'],
                'attr' => ['class' => 'form-select'],
            ])
            ->add('imageFile', FileType::class, [
                'label' => 'relic.form.image',
                'translation_domain' => 'relic',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '2M',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                            'image/webp',
                        ],
                        'mimeTypesMessage' => 'Please upload a valid image (JPEG, PNG, WEBP)',
                    ])
                ],
                'attr' => [
                    'class' => 'form-control',
                ],
                'help' => 'relic.form.image_help',
                'help_attr' => ['class' => 'form-text text-muted'],
                'label_attr' => ['class' => 'form-label'],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Relic::class,
        ]);
    }
}
