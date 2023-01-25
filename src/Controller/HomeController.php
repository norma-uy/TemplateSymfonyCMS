<?php

namespace App\Controller;

use App\Repository\PostRepository;
use DateTimeImmutable;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/{_locale}')]
class HomeController extends AbstractController
{
    public function __construct(private PostRepository $postRepository)
    {
    }

    #[
        Route(
            [
                'es' => '/inicio',
                'en' => '/home',
            ],
            name: 'home-page',
        ),
    ]
    public function index(): Response
    {
        $postsFeatured = $this->postRepository->findBy(['featured' => true]);

        $lastNews = $this->postRepository->findByDate(new DateTimeImmutable('now'), 5);

        return $this->render('home/index.html.twig', [
            'postsFeatured' => $postsFeatured,
            'lastNews' => $lastNews,
        ]);
    }
}
