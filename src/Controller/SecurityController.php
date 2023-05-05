<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SecurityController extends AbstractController
{
    #[Route('/login', name: 'app_login', methods: ['POST'])]
    public function login(#[CurrentUser] ?User $user): Response
    {
        if(!$user){
            return $this->json([
                'error' => "Add to your request : content-type: application/json",
            ], 401);
        }
        
        return $this->json([
            'user' =>'/api/users/'.$user->getId(),
        ]);
        //🧰for test purpose : header session is like this : cookie  → PHPSESSID=mtdllsu3jom9sflug33a0015o0
    }

    #[Route('/logout', name: 'app_logout', methods: ['POST','GET'])]
    public function logout(){
        //vide
        //https://youtu.be/bewgb9buIfI?t=1404
    }
}