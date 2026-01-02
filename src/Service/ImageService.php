<?php

namespace App\Service;

use App\Entity\Image;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ImageService
{
    public function __construct(
        private HttpClientInterface $httpClient,
        private EntityManagerInterface $entityManager,
        private string $nasaApiKey
    ) {
    }

    /**
     * Recupere l'image du jour depuis l'API de la NASA et la stocke en base de données.
     */
    public function getImage(): void
    {
        if ($this->isAlreadyGet()) {
            throw new \RuntimeException('L\'image du jour est déjà recuperée.');
        }

        $response = $this->httpClient->request('GET', 'https://api.nasa.gov/planetary/apod', [
            'query' => [
                'api_key' => $this->nasaApiKey,
            ],
        ]);

        if ($response !== null && $response->getStatusCode() === 200) {
            $data = $response->toArray();

            $image = new Image();
            $image->setDate(new \DateTime($data['date']));
            $image->setTitle($data['title']);
            $image->setExplanation($data['explanation']);
            $image->setImage($data['url']);

            $this->entityManager->persist($image);
            $this->entityManager->flush();
        } else {
            throw new \RuntimeException('Impossible de récupérer l\'image du jour.');
        }
    }

    /**
     * Vérifie si l'image du jour a déjà été récupérée.
     */
    private function isAlreadyGet(): bool
    {
        $imageRepository = $this->entityManager->getRepository(Image::class);
        $lastImage = $imageRepository->findLastImage();

        if ($lastImage !== null && $lastImage->getDate()->format('d/m/Y') === date('d/m/Y')) {
            return true;
        }

        return false;
    }
}