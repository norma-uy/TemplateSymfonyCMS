<?php

namespace App\Controller;

use App\Repository\PostRepository;
use DateTimeImmutable;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/{_locale}')]
class PostController extends AbstractController
{
    public function __construct(private PostRepository $postRepository)
    {
    }

    #[
        Route(
            [
                'es' => '/post/{slug}',
                'en' => '/post/{slug}',
            ],
            name: 'post-index-page',
        ),
    ]
    public function index(string $slug): Response
    {
        $post = $this->postRepository->findOneBy(['slug' => $slug]);

        if (!$post) {
            throw $this->createNotFoundException('El recursos solicitado no existe.');
        }

        $lastNews = $this->postRepository->findByDate(new DateTimeImmutable('now'), 5, $post);

        return $this->render('post/index.html.twig', [
            'post' => $post,
            'lastNews' => $lastNews,
        ]);
    }
}
