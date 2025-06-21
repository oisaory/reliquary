<?php

namespace App\Controller;

use App\Entity\Saint;
use App\Form\SaintType;
use App\Repository\SaintRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/saint')]
final class SaintController extends AbstractController
{
    #[Route(name: 'app_saint_index', methods: ['GET'])]
    public function index(Request $request, SaintRepository $saintRepository, PaginatorInterface $paginator): Response
    {
        $query = $saintRepository->createQueryBuilder('s')
            ->getQuery();

        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1), // Current page
            10 // Items per page
        );

        return $this->render('saint/index.html.twig', [
            'pagination' => $pagination,
        ]);
    }

    #[Route('/my-saints', name: 'app_my_saints', methods: ['GET'])]
    public function mySaints(Request $request, SaintRepository $saintRepository, PaginatorInterface $paginator): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        $user = $this->getUser();

        $query = $saintRepository->createQueryBuilder('s')
            ->where('s.creator = :user')
            ->setParameter('user', $user)
            ->getQuery();

        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1), // Current page
            10 // Items per page
        );

        return $this->render('saint/index.html.twig', [
            'pagination' => $pagination,
            'title' => 'My Saints'
        ]);
    }

    #[Route('/new', name: 'app_saint_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        $saint = new Saint();
        $form = $this->createForm(SaintType::class, $saint);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($saint);
            $entityManager->flush();

            return $this->redirectToRoute('app_saint_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('saint/new.html.twig', [
            'saint' => $saint,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_saint_show', methods: ['GET'])]
    public function show(Saint $saint): Response
    {
        return $this->render('saint/show.html.twig', [
            'saint' => $saint,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_saint_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Saint $saint, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        $form = $this->createForm(SaintType::class, $saint);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_saint_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('saint/edit.html.twig', [
            'saint' => $saint,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_saint_delete', methods: ['POST'])]
    public function delete(Request $request, Saint $saint, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        if ($this->isCsrfTokenValid('delete'.$saint->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($saint);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_saint_index', [], Response::HTTP_SEE_OTHER);
    }
}
