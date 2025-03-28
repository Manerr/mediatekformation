<?php

namespace App\Security;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\CustomCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class LoginAppAuthenticator extends AbstractAuthenticator
{
    private UserPasswordHasherInterface $passwordHasher;
    private UrlGeneratorInterface $urlGenerator;

    // Injection des dépendances dans le constructeur
    public function __construct(UserPasswordHasherInterface $passwordHasher, UrlGeneratorInterface $urlGenerator)
    {
        $this->passwordHasher = $passwordHasher;
        $this->urlGenerator = $urlGenerator;
    }

    public function supports(Request $request): ?bool
    {
        return ($request->getPathInfo() === '/login' && $request->isMethod('POST'));
    }

    public function authenticate(Request $request): Passport
    {
        $name = $request->get("name");
        $pwd = $request->get("pwd");
        $token = $request->get("token_");

        return new Passport(
            new UserBadge($name),
            new CustomCredentials(fn($credentials, \App\Entity\User $user) => $this->passwordHasher->isPasswordValid($user, $credentials), $pwd)
        );
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        return new RedirectResponse($this->urlGenerator->generate('admin.accueil'));  // Redirection après succès
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        return new RedirectResponse($this->urlGenerator->generate('accueil'));  // Redirection en cas d'échec
    }
}
