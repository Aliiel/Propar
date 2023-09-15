<?php

namespace App\DataFixtures;

use App\Entity\Utilisateur;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UtilisateurFixtures extends Fixture
{
    private $counter = 2;

 public function __construct(
    private UserPasswordHasherInterface $passwordEncoder
 ){}
    
    public function load(ObjectManager $manager): void
    {  
        
        $faker = Faker\Factory::create('fr_FR');

        $admin = new Utilisateur();
        $admin -> setId(1);
        $admin -> setEmail($faker -> email);
        $admin -> setRoles(['EXPERT']);
        $admin -> setPassword(
            $this -> passwordEncoder ->hashPassword($admin , 'admin')
        );
         $manager ->persist($admin);
         $manager ->flush();



        for ($i=2; $i <= 5 ; $i++) { 
            $utilisateur = new Utilisateur();
            $utilisateur -> setId($i);
          $utilisateur -> setEmail($faker -> email);
          $utilisateur -> setRoles(['SENIOR']);
           $utilisateur -> setPassword(
            $this -> passwordEncoder ->hashPassword($utilisateur , 'azerty')
        );
  
        $this -> counter++;
        $manager -> persist($utilisateur);
    }
    //     for ($i=6; $i <= 10 ; $i++) { 
    //         $utilisateur = new Utilisateur();
    //         $utilisateur -> setId($i);
    //       $utilisateur -> setEmail($faker -> email);
    //       $utilisateur -> setRoles(['APPRENTI']);
    //        $admin -> setPassword(
    //         $this -> passwordEncoder ->hashPassword($admin , $faker -> text)
    //     );
      
    //     $this -> addReference('id'.$this->counter, $utilisateur);
    //     $this -> counter++; 
        
    //     $manager -> persist($utilisateur);
    // }
        $manager->flush();
} 


}