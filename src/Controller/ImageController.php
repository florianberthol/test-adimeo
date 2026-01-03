<?php

namespace App\Controller;

use App\Repository\ImageRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class ImageController extends AbstractController
{
    #[Route('/', name: 'app_image')]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function index(ImageRepository $imageRepository): Response
    {
        $image = $imageRepository->findLastImage();

        if (!$image) {
            throw $this->createNotFoundException('Il n\'y a pas encore d\'image disponible.');
        }

        return $this->render('image/image.html.twig', [
            'title' => $image->getTitle(),
            'explanation' => $image->getExplanation(),
            'date' => $image->getDate(),
            'url' => $image->getUrl(),
        ]);
    }
}

