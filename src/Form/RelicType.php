<?php

namespace App\Form;

use App\Entity\Relic;
use App\Entity\Saint;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RelicType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('location', null, [
                'label' => 'Location',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Enter the location of the relic',
                ],
                'help' => 'Where is this relic currently located?',
                'help_attr' => ['class' => 'form-text text-muted'],
                'label_attr' => ['class' => 'form-label'],
            ])
            ->add('saint', EntityType::class, [
                'class' => Saint::class,
                'choice_label' => 'name',
                'label' => 'Saint',
                'attr' => ['class' => 'form-select'],
                'help' => 'Select the saint associated with this relic',
                'help_attr' => ['class' => 'form-text text-muted'],
                'label_attr' => ['class' => 'form-label'],
                'placeholder' => 'Choose a saint',
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
