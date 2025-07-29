<?php

namespace App\Controller;

use App\Entity\Relic;
use App\Enum\RelicStatus;
use App\Repository\RelicRepository;
use App\Service\RelicWorkflowService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/relic/workflow')]
final class RelicWorkflowController extends AbstractController
{
    #[Route('/pending', name: 'app_pending_relics', methods: ['GET'])]
    public function pendingRelics(RelicRepository $relicRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        return $this->render('relic/pending.html.twig', [
            'relics' => $relicRepository->findByStatus(RelicStatus::PENDING),
        ]);
    }

    #[Route('/{id}/approve', name: 'app_relic_approve', methods: ['GET', 'POST'])]
    public function approve(
        Request $request, 
        Relic $relic, 
        EntityManagerInterface $entityManager,
        RelicWorkflowService $workflowService
    ): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        if ($request->isMethod('POST')) {
            if ($this->isCsrfTokenValid('approve'.$relic->getId(), $request->getPayload()->getString('_token'))) {
                try {
                    $workflowService->approve($relic);
                    $entityManager->flush();
                    $this->addFlash('success', 'Relic approved successfully');
                } catch (\RuntimeException $e) {
                    $this->addFlash('error', $e->getMessage());
                }
            }
            return $this->redirectToRoute('app_pending_relics', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('relic/approve.html.twig', [
            'relic' => $relic,
            'can_approve' => $workflowService->canApprove($relic),
            'can_reject' => $workflowService->canReject($relic),
        ]);
    }

    #[Route('/{id}/reject', name: 'app_relic_reject', methods: ['POST'])]
    public function reject(
        Request $request, 
        Relic $relic, 
        EntityManagerInterface $entityManager,
        RelicWorkflowService $workflowService
    ): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        if ($this->isCsrfTokenValid('reject'.$relic->getId(), $request->getPayload()->getString('_token'))) {
            try {
                $reason = $request->getPayload()->getString('rejection_reason');
                $workflowService->reject($relic, $reason);
                $entityManager->flush();
                $this->addFlash('success', 'Relic not accepted');
            } catch (\RuntimeException $e) {
                $this->addFlash('error', $e->getMessage());
            }
        }

        return $this->redirectToRoute('app_pending_relics', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}/resubmit', name: 'app_relic_resubmit', methods: ['POST'])]
    public function resubmit(
        Request $request, 
        Relic $relic, 
        EntityManagerInterface $entityManager,
        RelicWorkflowService $workflowService
    ): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        if ($this->isCsrfTokenValid('resubmit'.$relic->getId(), $request->getPayload()->getString('_token'))) {
            try {
                $workflowService->resubmit($relic);
                $entityManager->flush();
                $this->addFlash('success', 'Relic resubmitted for approval');
            } catch (\RuntimeException $e) {
                $this->addFlash('error', $e->getMessage());
            }
        }

        return $this->redirectToRoute('app_relic_index', [], Response::HTTP_SEE_OTHER);
    }
}