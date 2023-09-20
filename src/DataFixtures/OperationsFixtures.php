<?php

namespace App\DataFixtures;

use App\Entity\Client;
use App\Entity\Operation;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker;

class OperationsFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        

        $clients = $manager->getRepository(Client::class)->findAll();
        $faker = Faker\Factory::create();

        $prixPossibles = [1000, 2500, 5000];

foreach ($clients as $client) {
  $operations = new Operation();
    $operations->setType($faker->randomElement($prixPossibles));
    $operations->setEtat($faker->numberBetween(1 , 2)); 

    $dateRealisationString = $faker->date(); // Chaîne de caractères représentant la date
    $dateRealisation = DateTime::createFromFormat('Y-m-d', $dateRealisationString); // Conversion en objet DateTime

    $operations->setDateRealisation($dateRealisation);
    
    $operations->setClient($client); 


    $manager->persist($operations);
}
   
  $manager->flush();
}

    public function getDependencies()
    {
        return array(
            UtilisateurFixtures::class,
            ClientFixtures::class,
        );
    }

}