<?php declare(strict_types=1);

namespace App\Component\User\Infrastructure;

use App\Entity\User;
use App\Repository\UserRepository;
use Firebase\JWT\JWT;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AuthController extends AbstractController
{
    private UserPasswordEncoderInterface $encoder;

    private UserRepository $userRepository;

    /**
     * @param \Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface $encoder
     * @param \App\Repository\UserRepository $userRepository
     */
    public function __construct(
        UserPasswordEncoderInterface $encoder,
        UserRepository $userRepository
    )
    {
        $this->encoder = $encoder;
        $this->userRepository = $userRepository;
    }

    /**
     * @Route("/auth/register", name="register", methods={"POST"})
     */
    public function register(Request $request): JsonResponse
    {
        $content = $this->getContent($request);
        $info = (array)json_decode($content, true);

        $password = $info['password'];
        $email = $info['email'];
        $username = $info['username'];

        $email = filter_var($email, FILTER_SANITIZE_EMAIL);

        if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
            return $this->json([
                'success' => false,
                'message' => sprintf('Email %s is not valid!', $email),
            ]);
        }

        if (empty(trim((string)$username))) {
            return $this->json([
                'success' => false,
                'message' => 'Username should not be empty!',
            ]);
        }

        if (empty(trim((string)$password))) {
            return $this->json([
                'success' => false,
                'message' => 'Password should not be empty!',
            ]);
        }

        if ($this->checkIfUserAlreadyExists('email', $email)) {
            return $this->json([
                'success' => false,
                'message' => sprintf('Email %s is already in use!', $email),
            ]);
        }

        if ($this->checkIfUserAlreadyExists('username', $username)) {
            return $this->json([
                'success' => false,
                'message' => sprintf('Username %s is already in use!', $username),
            ]);
        }

        $user = new User();
        $user->setPassword($this->encoder->encodePassword($user, $password));
        $user->setEmail($email);
        $user->setUsername($username);

        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();

        return $this->json([
            'success' => true,
            'data' => [
                'id' => $user->getId(),
                'username' => $user->getUsername(),
            ],
        ])->setEncodingOptions(JSON_UNESCAPED_SLASHES);
    }

    /**
     * @Route("/auth/login", name="login", methods={"POST"})
     */
    public function login(Request $request): JsonResponse
    {
        $content = $this->getContent($request);
        $info = (array)json_decode($content, true);

        if (!isset($info['email']) || !isset($info['password'])) {
            return $this->json([
                'success' => false,
                'message' => 'email or password is wrong.',
            ]);
        }

        $user = $this->userRepository->findOneBy([
            'email' => $info['email'],
        ]);

        if (!$user instanceof User || !$this->encoder->isPasswordValid($user, $info['password'])) {
            return $this->json([
                'success' => false,
                'message' => 'email or password is wrong.',
            ]);
        }

        $payload = [
            "userId" => $user->getId(),
        ];

        $jwt = JWT::encode($payload, $this->getParameter('kernel.secret'), 'HS256');

        $user->setToken($jwt);
        $user->setTokenTimeAllowed(new \DateTime('+ 15 Minutes'));

        $this->getDoctrine()->getManager()->persist($user);
        $this->getDoctrine()->getManager()->flush();

        return $this->json([
            'success' => true,
            'token' => sprintf('Bearer %s', $jwt),
        ]);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return string
     */
    private function getContent(Request $request): string
    {
        return $request->getContent();
    }

    /**
     * @param string $fieldName
     * @param $value
     *
     * @return bool
     */
    private function checkIfUserAlreadyExists(string $fieldName, $value): bool
    {
        $user = $this->userRepository->findOneBy(
            [
                $fieldName => $value
            ]
        );

        return $user instanceof User;
    }

}
