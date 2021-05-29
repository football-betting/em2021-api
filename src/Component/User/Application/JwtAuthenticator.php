<?php declare(strict_types=1);

namespace App\Component\User\Application;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Firebase\JWT\JWT;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;

class JwtAuthenticator extends AbstractGuardAuthenticator
{
    private EntityManagerInterface $em;
    private ParameterBagInterface $parameterBag;
    private UserRepository $userRepository;

    public function __construct(EntityManagerInterface $em, ParameterBagInterface $parameterBag, UserRepository $userRepository)
    {
        $this->em = $em;
        $this->parameterBag = $parameterBag;
        $this->userRepository = $userRepository;
    }

    public function start(Request $request, AuthenticationException $authException = null)
    {
        $data = [
            'message' => 'Authentication Required',
        ];
        return new JsonResponse($data, Response::HTTP_UNAUTHORIZED);
    }

    public function supports(Request $request)
    {
        return $request->headers->has('Authorization') || $request->server->has('Authorization');
    }

    public function getCredentials(Request $request)
    {
        return $request->headers->get('Authorization') ?? $request->server->get('Authorization');
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        try {
            $credentials = str_replace('Bearer ', '', $credentials);
            $jwt = (array)JWT::decode(
                $credentials,
                $this->parameterBag->get('kernel.secret'),
                ['HS256']
            );

            return $this->userRepository->find($jwt['userId']);


        } catch (\Exception $exception) {
            throw new AuthenticationException($exception->getMessage());
        }
    }

    public function checkCredentials($credentials, UserInterface $user)
    {
        if (!$user instanceof User) {
            throw new \Exception('Token not allowed');
        }

        $tokenDateTime = $user->getTokenTimeAllowed();
        if ($tokenDateTime < new \DateTime()) {
            throw new AuthenticationException('Token time is expired');
        }

        return true;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        return new JsonResponse([
            'message' => $exception->getMessage()
        ], Response::HTTP_UNAUTHORIZED);
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $providerKey)
    {
        return;
    }

    public function supportsRememberMe()
    {
        return false;
    }
}
