<?php declare(strict_types=1);

namespace App\Component\User\Infrastructure;

use App\Entity\User;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AuthController extends AbstractController
{
    private UserPasswordEncoderInterface $encoder;

    /**
     * @param \Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface $encoder
     */
    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
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

        $user = new User();
        $user->setPassword($this->encoder->encodePassword($user, $password));
        $user->setEmail($email);
        $user->setUsername($username);

        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();

        return $this->json([
            'id' => $user->getId(),
            'user' => $user->getEmail(),
        ])->setEncodingOptions(JSON_UNESCAPED_SLASHES);;
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
}
