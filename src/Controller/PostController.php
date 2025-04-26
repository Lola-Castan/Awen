<?php

namespace App\Controller;

use App\Entity\Post;
use App\Entity\User;
use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/posts')]
class PostController extends AbstractController
{
    #[Route('/', name: 'app_post_index', methods: ['GET'])]
    public function index(PostRepository $postRepository): Response
    {
        return $this->render('post/index.html.twig', [
            'posts' => $postRepository->findPublished(),
        ]);
    }

    #[Route('/author/{id}', name: 'app_post_by_author', methods: ['GET'])]
    public function byAuthor(User $user, PostRepository $postRepository): Response
    {
        return $this->render('post/index.html.twig', [
            'posts' => $postRepository->findByAuthor($user),
            'author' => $user,
        ]);
    }

    #[Route('/{id}', name: 'app_post_show', methods: ['GET'])]
    public function show(Post $post): Response
    {
        // Vérifie que le post est publié ou que l'utilisateur est l'auteur
        if (!$post->isIsPublished() && 
            ($this->getUser() === null || $post->getAuthor() !== $this->getUser())) {
            throw $this->createAccessDeniedException('Ce post n\'est pas accessible.');
        }
        
        return $this->render('post/show.html.twig', [
            'post' => $post,
        ]);
    }
}