<?php

namespace App\Form;

use App\Entity\Relic;
use App\Entity\Saint;
use App\Form\SaintAutocompleteField;
use App\Form\AddressAutocompleteType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class RelicType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('address', AddressAutocompleteType::class, [
                'label' => 'Address',
                'help' => 'The general address where this relic is located',
                'help_attr' => ['class' => 'form-text text-muted'],
                'label_attr' => ['class' => 'form-label'],
            ])
            ->add('location', null, [
                'label' => 'Specific Location',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Enter the specific location within the address',
                ],
                'help' => 'Where specifically within the address is this relic located? (e.g., "North Chapel", "Main Altar", etc.)',
                'help_attr' => ['class' => 'form-text text-muted'],
                'label_attr' => ['class' => 'form-label'],
            ])
            ->add('saint', SaintAutocompleteField::class, [
                'label' => 'Saint',
                'help' => 'Select the saint associated with this relic',
                'help_attr' => ['class' => 'form-text text-muted'],
                'label_attr' => ['class' => 'form-label'],
            ])
            ->add('imageFile', FileType::class, [
                'label' => 'Relic Image',
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
                'help' => 'Upload an image of the relic (max size: 2MB)',
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
