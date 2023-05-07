<?php

namespace App\Controller\cycleEtudes;

use App\Entity\CycleEtude;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;



class AddEtudiant extends AbstractController{
    public function __construct(private Security $security)
    {   
    }

    public function __invoke(CycleEtude $cycle)
    {
        // return(dd($this->security->getUser()));
        $etudiant = $this->security->getUser();
        $cycle->addEtudiant($etudiant);
        return $cycle;
    }
}