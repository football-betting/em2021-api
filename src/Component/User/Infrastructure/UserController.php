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
        /** @var User $user */
        $user = $this->getUser();

        return $this->json([
            'success' => true,
            'data' => [
                'id' => $user->getId(),
                'username' => $user->getUsername(),
                'email' => $user->getEmail(),
                'winningTeam' => $user->getTip1(),
                'secretWinningTeam' => $user->getTip2(),
            ],
        ]);
    }
}
