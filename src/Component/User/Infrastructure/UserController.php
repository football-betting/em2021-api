<?php declare(strict_types=1);

namespace App\Component\User\Infrastructure;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    /**
     * @Route("/api/user/info", name="user_info", methods={"GET"})
     */
    public function info()
    {
        $user = $this->getLoginUser();

        return $this->json([
            'data' => [
                'id' => $user->getId(),
                'username' => $user->getUsername(),
                'email' => $user->getEmail(),
            ],
        ]);
    }

    private function getLoginUser(): User
    {
        $user = $this->getUser();
        if (!$user instanceof User) {
            throw new \Exception('User not loggin');
        }

        return $user;
    }
}
