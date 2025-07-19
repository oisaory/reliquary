<?php

namespace App\Controller;

use App\Entity\Relic;
use App\Form\RelicType;
use App\Repository\RelicRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/relic')]
final class RelicController extends AbstractController
{
    #[Route(name: 'app_relic_index', methods: ['GET'])]
    public function index(RelicRepository $relicRepository): Response
    {
        return $this->render('relic/index.html.twig', [
            'relics' => $relicRepository->findAll(),
        ]);
    }

    #[Route('/desktop', name: 'app_relic_desktop', methods: ['GET'])]
    public function desktopList(RelicRepository $relicRepository): Response
    {
        return $this->render('relic/_relic_list_desktop.html.twig', [
            'relics' => $relicRepository->findAll(),
        ]);
    }

    #[Route('/mobile', name: 'app_relic_mobile', methods: ['GET'])]
    public function mobileList(RelicRepository $relicRepository): Response
    {
        return $this->render('relic/_relic_list_mobile.html.twig', [
            'relics' => $relicRepository->findAll(),
        ]);
    }

    #[Route('/my-relics', name: 'app_my_relics', methods: ['GET'])]
    public function myRelics(RelicRepository $relicRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        $user = $this->getUser();

        return $this->render('relic/index.html.twig', [
            'relics' => $relicRepository->findBy(['creator' => $user]),
            'title' => 'My Relics'
        ]);
    }

    #[Route('/my-relics/desktop', name: 'app_my_relics_desktop', methods: ['GET'])]
    public function myRelicsDesktop(RelicRepository $relicRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        $user = $this->getUser();

        return $this->render('relic/_relic_list_desktop.html.twig', [
            'relics' => $relicRepository->findBy(['creator' => $user]),
            'title' => 'My Relics'
        ]);
    }

    #[Route('/my-relics/mobile', name: 'app_my_relics_mobile', methods: ['GET'])]
    public function myRelicsMobile(RelicRepository $relicRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        $user = $this->getUser();

        return $this->render('relic/_relic_list_mobile.html.twig', [
            'relics' => $relicRepository->findBy(['creator' => $user]),
            'title' => 'My Relics'
        ]);
    }

    #[Route('/new', name: 'app_relic_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        $relic = new Relic();
        $form = $this->createForm(RelicType::class, $relic);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($relic);
            $entityManager->flush();

            return $this->redirectToRoute('app_relic_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('relic/new.html.twig', [
            'relic' => $relic,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_relic_show', methods: ['GET'])]
    public function show(Relic $relic): Response
    {
        return $this->render('relic/show.html.twig', [
            'relic' => $relic,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_relic_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Relic $relic, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        $form = $this->createForm(RelicType::class, $relic);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_relic_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('relic/edit.html.twig', [
            'relic' => $relic,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_relic_delete', methods: ['POST'])]
    public function delete(Request $request, Relic $relic, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        if ($this->isCsrfTokenValid('delete'.$relic->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($relic);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_relic_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/saint/{id}/desktop', name: 'app_saint_relics_desktop', methods: ['GET'])]
    public function saintRelicsDesktop(int $id, RelicRepository $relicRepository): Response
    {
        return $this->render('relic/_relic_list_desktop.html.twig', [
            'relics' => $relicRepository->findBy(['saint' => $id]),
        ]);
    }

    #[Route('/saint/{id}/mobile', name: 'app_saint_relics_mobile', methods: ['GET'])]
    public function saintRelicsMobile(int $id, RelicRepository $relicRepository): Response
    {
        return $this->render('relic/_relic_list_mobile.html.twig', [
            'relics' => $relicRepository->findBy(['saint' => $id]),
        ]);
    }
}
