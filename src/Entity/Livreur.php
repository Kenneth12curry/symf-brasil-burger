<?php

namespace App\Entity;

use App\Repository\LivreurRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LivreurRepository::class)]
class Livreur extends User
{
   

    public function __construct()
    {
        $this->roles[]="Livreur";

    }

    #[ORM\Column(length: 255)]
    private ?string $matriculeMoto = null;

    
    public function getMatriculeMoto(): ?string
    {
        return $this->matriculeMoto;
    
    }

    public function setMatriculeMoto(string $matriculeMoto): self
    {
        $this->matriculeMoto = $matriculeMoto;

        return $this;
    }
}
