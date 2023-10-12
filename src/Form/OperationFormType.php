<?php

namespace App\Form;

use App\Entity\Client;
use App\Entity\Operation;
use App\Entity\Utilisateur;
use App\Repository\ClientRepository;
use App\Repository\UtilisateurRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OperationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('type', ChoiceType::class, [
            'choices' => [
                'Petite Operation' => 1000,
                'Moyenne Operation' => 2500,
                'Grosse Operation' => 10000,
            ],
            'label' => "Sélectionner le type d'opération : ",
            'placeholder' => 'Choisissez une opération', // Optionnel : pour afficher un champ vide au début
        ])

            // ->add('utilisateur', EntityType::class, [
            //     'class' => Utilisateur::class,
            //     'choice_label' => 'nom',
            //     'query_builder' => function(UtilisateurRepository $utilisateurRepository)
            //     {
            //         return $utilisateurRepository->createQueryBuilder('u')
            //             ->orderBy('u.nom', 'ASC');
            //     }
            // ])

            ->add('date_realisation', null, [
                 'label' => 'Date de réalisation : ',
            ])
           
            
            ->add('client', EntityType::class, [
            'class' => Client::class, 
            'choice_label' => 'Nom',  
            'query_builder' => function(ClientRepository $clientRepository) 
            {
                return $clientRepository->createQueryBuilder('c')
                    ->orderBy('c.Nom', 'ASC'); // Trie par le champ 'nom' du Client en ordre croissant (ASC).
            }]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Operation::class,
        ]);
    }
}
