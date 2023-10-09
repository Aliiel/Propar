<?php

namespace App\Form;

use App\Entity\Client;
use App\Repository\ClientRepository;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AjoutClientFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Nom', TextType::class, [
                'label' => 'Nom :'
                ])
            ->add('prenom', TextType::class, [
                'label' => 'Prénom :'
                ])
            ->add('ville', TextType::class, [
                'label' => 'Ville :'
                ])
            ->add('code_postal', TextType::class, [
                'label' => 'Code postal :'
                ])
            ->add('pays', TextType::class, [
                'label' => 'Pays :'
                ])
            ->add('numero_voie', TextType::class, [
                'label' => 'Numéro de voie :'
                ])
            ->add('adresse', TextType::class, [
                'label' => 'Adresse :'
                ])
            ->add('email', TextType::class, [
                'label' => 'E-mail :'
                ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Client::class,
        ]);
    }
}
