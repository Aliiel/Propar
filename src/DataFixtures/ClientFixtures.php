<?php

namespace App\DataFixtures;

use App\Entity\Client;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker;

class ClientFixtures extends Fixture implements DependentFixtureInterface
{
    private $counter = 1;
    public function load(ObjectManager $manager): void
    {
    

        
$faker = Faker\Factory::create('fr_FR');

for ($i=1; $i <12 ; $i++) { 
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
    $this -> addReference('id'.$this->counter, $client);
    $this -> counter++;


$utilisateur = $this->getReference('id'.rand(1,5));
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
