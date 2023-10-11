<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;


class FiltreType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('dateEntree', DateType::class, [
                'widget' => 'choice',
                'input' => 'datetime_immutable',
                'format' => 'yyyy-MM-dd',
                'required' => true,
                // 'data' => new \DateTimeImmutable(),

            ])

            ->add('dateSortie', DateType::class, [
                'widget' => 'choice',
                'input' => 'datetime_immutable',
                'format' => 'yyyy-MM-dd',
                'required' => true,
                // 'data' => new \DateTimeImmutable(),

            ])

            ->add('submit', SubmitType::class, [
                'label' => 'Confirmer',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}