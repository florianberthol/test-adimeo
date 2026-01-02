<?php
namespace App\Controller;

use League\OAuth2\Client\Provider\Google;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class GoogleConnectController extends AbstractController
{
    #[Route('/connect/google', name: 'connect_google_start')]
    public function connectAction(Request $request, string $googleClientId, string $googleClientSecret): Response
    {
        $provider = new Google([
            'clientId'     => $googleClientId,
            'clientSecret' => $googleClientSecret,
            'redirectUri'  => $this->generateUrl('connect_google_check', [], UrlGeneratorInterface::ABSOLUTE_URL),
        ]);

        $authUrl = $provider->getAuthorizationUrl([
            'scope' => ['openid', 'email', 'profile'],
        ]);

        $request->getSession()->set('oauth2state', $provider->getState());

        return $this->redirect($authUrl);
    }

    #[Route('/connect/google/check', name: 'connect_google_check')]
    public function connectCheck(): Response
    {
        return $this->redirectToRoute('app_image');
    }
}