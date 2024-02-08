<?php

namespace App\Controller\Frontend;

use App\Entity\Book;
use App\Form\BookType;
use App\Repository\BookRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/book', name: 'book')]
class BookController extends AbstractController
{
    public function __construct(
        private BookRepository $repo,
        private EntityManagerInterface $em
    ) {
    }

    #[Route('', name: '.index', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('Book/index.html.twig', [
            'page_title' => 'Livres',
            'books' => $this->repo->findAll(),
        ]);
    }

    #[Route ('/create', name: '.create', methods: ['GET', 'POST'])]
    public function create(Request $request): Response|RedirectResponse
    {
        $book = new Book();

        $form = $this->createForm(BookType::class, $book);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($book);
            $this->em->flush();
            $this->addFlash('success', 'Livre ajouté aux étagères avec succès');

            return $this->redirectToRoute('book.index');
        }

        return $this->render('Book/create.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/{id}/update', name: '.update', methods: ['GET', 'POST'])]
    public function update(?Book $book, Request $request): Response|RedirectResponse
    {
        $form = $this->createForm(BookType::class, $book);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($book);
            $this->em->flush();
            $this->addFlash('success', 'Livre modifié avec succès');

            return $this->redirectToRoute('book.index');
        }

        return $this->render('Book/update.html.twig', [
            'form' => $form,
            'book' => $book,
        ]);
    }

    #[Route('/{id}/delete', name: '.delete', methods: ['POST'])]
    public function delete(?Book $book, Request $request): Response|RedirectResponse
    {
        $this->em->remove($book);
        $this->em->flush();
        $this->addFlash('success', 'Livre supprimé avec succès');

        return $this->redirectToRoute('book.index');
    }
}
