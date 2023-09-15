<?php

namespace App\DataFixtures;

use App\Entity\Operation;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;

class OperationsFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        

        $faker = Faker\Factory::create();

for ($i=1; $i <= 10; $i++) { 
    $operations = new Operation();
    $operations->setType($faker->numberBetween(1 , 3));
    $operations->setEtat($faker->numberBetween(1 , 2)); 
    // Mettre l'etat en boolean fini ou non fini 

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
