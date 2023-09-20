<?php

namespace App\DataFixtures;

use App\Entity\Client;
use App\Entity\Utilisateur;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker;

class ClientFixtures extends Fixture implements DependentFixtureInterface
{



    public function load(ObjectManager $manager): void
    {
        $utilisateurs = $manager->getRepository(Utilisateur::class)->findAll();

        
$faker = Faker\Factory::create('fr_FR');

foreach ($utilisateurs as $utilisateur) { 
    $client = new Client();
    //Trouver les relations avec symfony 
    $client -> setNom($faker -> lastName);
    $client -> setPrenom($faker -> firstName);
    $client -> setVille($faker -> city);
    $client -> setCodePostal(str_replace(' ', '',$faker -> postcode));
    $client -> setPays($faker->country);
    $client -> setNumeroVoie($faker->numberBetween(1 , 320));
    $client -> setAdresse($faker->streetAddress);
    $client -> setEmail($faker->email);
    $client->setUtilisateur($utilisateur);


 $manager->persist($client); 
} 
       $manager->flush(); 
    }

    public function getDependencies()
    {
        return array(
         
UtilisateurFixtures::class,
        );
    }
}
