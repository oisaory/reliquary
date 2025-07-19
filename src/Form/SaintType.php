<?php

namespace App\Form;

use App\Entity\Saint;
use App\Enum\CanonicalStatus;
use Doctrine\DBAL\Types\StringType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class SaintType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', null, [
                'label' => 'Name',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Enter the name of the saint',
                ],
                'help' => 'Full name of the saint',
                'help_attr' => ['class' => 'form-text text-muted'],
                'label_attr' => ['class' => 'form-label'],
            ])
            ->add('url', null, [
                'label' => 'URL',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Enter the URL for the saint',
                ],
                'help' => 'Web address with information about the saint',
                'help_attr' => ['class' => 'form-text text-muted'],
                'label_attr' => ['class' => 'form-label'],
            ])
            ->add('file', null, [
                'label' => 'File',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Enter the file name',
                ],
                'help' => 'File name associated with the saint',
                'help_attr' => ['class' => 'form-text text-muted'],
                'label_attr' => ['class' => 'form-label'],
            ])
            ->add('canonical_status', EnumType::class, [
                'label' => 'Canonical Status',
                'class' => CanonicalStatus::class,
                'choice_label' => fn(CanonicalStatus $status) => $status->getLabel(),
                'attr' => [
                    'class' => 'form-control',
                ],
                'help' => 'Current canonical status (e.g., Canonization, Beatification)',
                'help_attr' => ['class' => 'form-text text-muted'],
                'label_attr' => ['class' => 'form-label'],
                'required' => false,
            ])
            ->add('canonization_date', DateType::class, [
                'label' => 'Canonization Date',
                'widget' => 'single_text',
                'attr' => [
                    'class' => 'form-control',
                ],
                'help' => 'Date when the saint was canonized',
                'help_attr' => ['class' => 'form-text text-muted'],
                'label_attr' => ['class' => 'form-label'],
                'required' => false,
            ])
            ->add('canonizing_pope', null, [
                'label' => 'Canonizing Pope',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Enter the name of the pope who canonized the saint',
                ],
                'help' => 'Name of the pope who performed the canonization',
                'help_attr' => ['class' => 'form-text text-muted'],
                'label_attr' => ['class' => 'form-label'],
            ])
            ->add('saint_phrase', null, [
                'label' => 'Saint Phrase',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Enter a notable phrase by or about the saint',
                ],
                'help' => 'A notable quote or phrase associated with the saint',
                'help_attr' => ['class' => 'form-text text-muted'],
                'label_attr' => ['class' => 'form-label'],
            ])
            ->add('abstract', null, [
                'label' => 'Abstract',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Enter a brief description of the saint',
                ],
                'help' => 'Brief summary about the saint',
                'help_attr' => ['class' => 'form-text text-muted'],
                'label_attr' => ['class' => 'form-label'],
            ])
            ->add('biography', null, [
                'label' => 'Biography',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Enter the biography of the saint',
                ],
                'help' => 'Detailed biography of the saint',
                'help_attr' => ['class' => 'form-text text-muted'],
                'label_attr' => ['class' => 'form-label'],
            ])
            ->add('image_link', null, [
                'label' => 'Image Link',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Enter the URL for the saint\'s image',
                ],
                'help' => 'Web address for an image of the saint',
                'help_attr' => ['class' => 'form-text text-muted'],
                'label_attr' => ['class' => 'form-label'],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Saint::class,
        ]);
    }
}
