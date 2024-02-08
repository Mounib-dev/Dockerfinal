<?php

namespace App\Controller\Frontend;

use App\Repository\BookRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{

    public function __construct(
        private BookRepository $bookRepo,
    ) {
    }

    #[Route('', name: 'app.homepage')]
    public function index(): Response
    {
        return $this->render('Home/index.html.twig', [
            'page_title' => 'Page d\'accueil',
            'books' => $this->bookRepo->findBestSellers()
        ]);
    }
}
