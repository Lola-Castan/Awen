<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CreatorController extends AbstractController
{
    #[Route('/creators', name: 'creators')]
    public function index(UserRepository $userRepository): Response
    {
        // Récupérer tous les utilisateurs qui sont des créateurs actifs
        $creators = $userRepository->findCreators();
        
        return $this->render('creator/index.html.twig', [
            'creators' => $creators,
        ]);
    }
    
    #[Route('/creator/{id}', name: 'creator_show')]
    public function show(User $user): Response
    {
        // Vérifier si l'utilisateur est bien un créateur
        if (!$user->isCreator()) {
            throw $this->createNotFoundException('Cet utilisateur n\'est pas un créateur.');
        }
        
        return $this->render('creator/show.html.twig', [
            'creator' => $user,
        ]);
    }
}