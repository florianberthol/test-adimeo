<?php

namespace App\Security;

use League\OAuth2\Client\Provider\Google;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\InMemoryUser;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;
use Symfony\Component\Security\Http\EntryPoint\AuthenticationEntryPointInterface;
use Symfony\Component\HttpFoundation\Response;

class GoogleAuthenticator extends AbstractAuthenticator implements AuthenticationEntryPointInterface
{
    public function __construct(
        private readonly UrlGeneratorInterface $urlGenerator,
        private readonly string $googleClientId,
        private readonly string $googleClientSecret
    ) {}

    public function supports(Request $request): bool
    {
        return $request->attributes->get('_route') === 'connect_google_check';
    }

    public function authenticate(Request $request): SelfValidatingPassport
    {
        $session = $request->getSession();
        $state = $request->query->get('state');
        if (!$state || $state !== $session->get('oauth2state')) {
            throw new AuthenticationException('Invalid OAuth state.');
        }
        $session->remove('oauth2state');

        $code = $request->query->get('code');
        if (!$code) {
            throw new AuthenticationException('No code returned from provider.');
        }

        $provider = new Google([
            'clientId'     => $this->googleClientId,
            'clientSecret' => $this->googleClientSecret,
            'redirectUri'  => $this->urlGenerator->generate('connect_google_check', [], UrlGeneratorInterface::ABSOLUTE_URL),
        ]);

        $accessToken = $provider->getAccessToken('authorization_code', ['code' => $code]);
        $owner = $provider->getResourceOwner($accessToken);
        $email = $owner->getEmail();
        if (!$email) {
            throw new AuthenticationException('No email from Google.');
        }

        return new SelfValidatingPassport(
            new UserBadge($email, fn () => new InMemoryUser($email, '', ['ROLE_USER']))
        );
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): Response
    {
        $targetPath = $request->getSession()->get('_security.main.target_path');
        if ($targetPath) {
            return new RedirectResponse($targetPath);
        }

        return new RedirectResponse($this->urlGenerator->generate('app_image'));
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): Response
    {
        return new RedirectResponse($this->urlGenerator->generate('app_image'));
    }

    public function start(Request $request, ?AuthenticationException $authException = null): Response
    {
        return new RedirectResponse($this->urlGenerator->generate('connect_google_start'));
    }
}