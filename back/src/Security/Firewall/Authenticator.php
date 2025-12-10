<?php

namespace App\Security\Firewall;

use Exception;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;

class Authenticator extends AbstractAuthenticator
{
    public function __construct(
        private $secret,
    ) {
    }

    public function supports(Request $request): ?bool
    {
        return $request->headers->has('Authorization') || !$request->headers->has('x-api-key');
    }

    public function authenticate(Request $request): Passport
    {
        $token = $request->headers->get('Authorization');

        if (!isset($token)) {
            throw new AuthenticationException();
        }
        
        try {
            $decoded = JWT::decode(trim(str_replace('Bearer', '', $token)), new Key($this->secret, 'HS256'));

            return new SelfValidatingPassport(new UserBadge($decoded->email));
        } catch (Exception $err) {
            throw new AuthenticationException();
        }
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        return throw new AuthenticationException("Unauthenticated", JsonResponse::HTTP_UNAUTHORIZED);
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        return null;
    }
}
