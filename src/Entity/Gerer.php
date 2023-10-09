<?php

namespace App\Entity;

use App\Repository\GererRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GererRepository::class)]
class Gerer
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    
    #[ORM\ManyToOne(inversedBy: 'gerers')]
    private ?Operation $operation_key = null;

    #[ORM\ManyToOne(inversedBy: 'gerers')]
    private ?Utilisateur $utilisateur_key = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOperationKey(): ?Operation
    {
        return $this->operation_key;
    }

    public function setOperationKey(?Operation $operation_key): static
    {
        $this->operation_key = $operation_key;

        return $this;
    }

    public function getUtilisateurKey(): ?Utilisateur
    {
        return $this->utilisateur_key;
    }

    public function setUtilisateurKey(?Utilisateur $utilisateur_key): static
    {
        $this->utilisateur_key = $utilisateur_key;

        return $this;
    }
}
