<?php

namespace App\Controller;

use App\Entity\Image;
use App\Form\ProfileType;
use App\Service\ImageService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Contracts\Translation\TranslatorInterface;

#[Route('/profile')]
#[IsGranted('ROLE_USER')]
class ProfileController extends AbstractController
{
    private TranslatorInterface $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }
    #[Route('/', name: 'app_profile_show', methods: ['GET'])]
    public function show(): Response
    {
        $user = $this->getUser();

        return $this->render('profile/show.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/edit', name: 'app_profile_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, EntityManagerInterface $entityManager, ImageService $imageService): Response
    {
        $user = $this->getUser();
        $form = $this->createForm(ProfileType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Handle profile image upload
            $profileImageFile = $form->get('profileImage')->getData();

            if ($profileImageFile) {
                // Remove existing profile image if any
                if (!$user->getImages()->isEmpty()) {
                    $existingImage = $user->getImages()->first();
                    $imageService->deleteImage($existingImage);
                    $user->removeImage($existingImage);
                    $entityManager->remove($existingImage);
                }

                // Create new UserImage entity using the ImageService
                $image = $imageService->createUserImage($profileImageFile, $user, $this->getUser());
                $user->addImage($image);
            }

            $entityManager->flush();

            $this->addFlash('success', $this->translator->trans('success', [], 'profile'));
            return $this->redirectToRoute('app_profile_show');
        }

        return $this->render('profile/edit.html.twig', [
            'form' => $form,
        ]);
    }
}
