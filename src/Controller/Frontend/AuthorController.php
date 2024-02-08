<?php

namespace App\Controller\Frontend;


use App\Entity\Author;
use App\Form\AuthorType;
use App\Repository\AuthorRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/author', name: 'author')]

class AuthorController extends AbstractController
{
    public function __construct(
        private AuthorRepository $repo,
        private EntityManagerInterface $em
    ) {
    }

    #[Route('', name: '.index', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('Author/index.html.twig', [
            'page_title' => 'Auteurs',
            'authors' => $this->repo->findAll(),
        ]);
    }

    #[Route('/create', name: '.create', methods: ['GET', 'POST'])]
    public function create(Request $request): Response|RedirectResponse
    {
        $auteur = new Author();

        $form = $this->createForm(AuthorType::class, $auteur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($auteur);
            $this->em->flush();
            $this->addFlash('success', 'Auteur ajouté avec succès');

            return $this->redirectToRoute('author.index');
        }

        return $this->render('Author/create.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/{id}/update', name: '.update', methods: ['GET', 'POST'])]
    public function update(?Author $author, Request $request): Response|RedirectResponse
    {
        $form = $this->createForm(AuthorType::class, $author);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($author);
            $this->em->flush();
            $this->addFlash('success', 'Coordoonées de l\'auteur modifiées avec succès');

            return $this->redirectToRoute('author.index');
        }

        return $this->render('Author/update.html.twig', [
            'form' => $form,
            'author' => $author,
        ]);
    }

    #[Route('/{id}/delete', name: '.delete', methods: ['POST'])]
    public function delete(?Author $author, Request $request): Response|RedirectResponse
    {
        $this->em->remove($author);
        $this->em->flush();
        $this->addFlash('success', 'Auteur supprimé avec succès');

        return $this->redirectToRoute('author.index');
    }
}
