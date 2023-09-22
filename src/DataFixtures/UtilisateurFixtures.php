<?php

namespace App\DataFixtures;

use App\Entity\Utilisateur;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UtilisateurFixtures extends Fixture
{
  

 public function __construct(
    private UserPasswordHasherInterface $passwordEncoder
 ){}
    
    public function load(ObjectManager $manager): void
    {  
        
        $faker = Faker\Factory::create('fr_FR');

        $admin = new Utilisateur();
        $admin -> setId(1);
        $admin -> setPrenom($faker -> firstName);
        $admin -> setNom($faker -> lastName);
        $admin -> setEmail($faker -> email);
        $admin -> setRoles('EXPERT');
        $admin -> setPassword(
            $this -> passwordEncoder ->hashPassword($admin , 'admin')
        );
         $manager ->persist($admin);
         $manager ->flush();



        for ($i=2; $i <= 5 ; $i++) { 
            $utilisateur = new Utilisateur();
            $utilisateur -> setId($i);
            $utilisateur -> setPrenom($faker -> firstName);
            $utilisateur-> setNom($faker -> lastName);
          $utilisateur -> setEmail($faker -> email);
          $utilisateur -> setRoles('SENIOR');
           $utilisateur -> setPassword(
            $this -> passwordEncoder ->hashPassword($utilisateur , 'azerty')
        );
        $manager ->persist($utilisateur);
    }
    $manager->flush();


        for ($i=6; $i <= 10 ; $i++) { 
            $utilisateur2 = new Utilisateur();
            $utilisateur2 -> setId($i);
            $utilisateur2 -> setPrenom($faker -> firstName);
            $utilisateur2-> setNom($faker -> lastName);
          $utilisateur2 -> setEmail($faker -> email);
         $utilisateur2 -> setRoles('APPRENTI');
           $utilisateur2 -> setPassword(
            $this -> passwordEncoder ->hashPassword($utilisateur2 , 'azerty')
        );
            $manager ->persist($utilisateur2);
} 
$manager->flush();

    }
}