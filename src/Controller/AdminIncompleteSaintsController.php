<?php

namespace App\Controller;

use App\Entity\Saint;
use App\Repository\SaintRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

/**
 * Controller for managing incomplete saints in the admin area
 */
#[Route('/admin/saints')]
#[IsGranted('ROLE_ADMIN')]
class AdminIncompleteSaintsController extends AbstractController
{
    /**
     * Lists all incomplete saints
     */
    #[Route('/incomplete', name: 'app_admin_saints_incomplete')]
    public function incompleteSaints(
        Request $request,
        SaintRepository $saintRepository,
        PaginatorInterface $paginator
    ): Response {
        $query = $saintRepository->findIncompleteQuery();
        
        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            10
        );
        
        return $this->render('admin/saints/incomplete.html.twig', [
            'pagination' => $pagination,
        ]);
    }
    
    /**
     * Marks a saint as complete
     */
    #[Route('/incomplete/{id}/complete', name: 'app_admin_saints_mark_complete')]
    public function markComplete(
        Saint $saint,
        EntityManagerInterface $entityManager
    ): Response {
        $saint->setIsIncomplete(false);
        $entityManager->flush();
        
        $this->addFlash('success', 'Saint marked as complete.');
        
        return $this->redirectToRoute('app_admin_saints_incomplete');
    }
}