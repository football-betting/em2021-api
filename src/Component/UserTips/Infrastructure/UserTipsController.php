<?php declare(strict_types=1);

namespace App\Component\UserTips\Infrastructure;

use App\Component\UserTips\Application\Tips;
use App\DataTransferObject\UserInfoDataProvider;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class UserTipsController extends AbstractController
{
    private Tips $tips;

    /**
     * @param \App\Component\UserTips\Application\Tips $tips
     */
    public function __construct(Tips $tips)
    {
        $this->tips = $tips;
    }

    /**
     * @Route("/api/user_tip/all", name="user_tip_all", methods={"GET"})
     */
    public function userTips(): JsonResponse
    {
        /** @var \App\Entity\User $user */


        $dataResponse = [
            'success' => true,
        ];
        $status = 200;
        try {
            $user = $this->getUser();
            $userInfoDataProvider = $this->tips->getUserTips($user->getUsername());
            $userInfo = $this->convertUserInfoDataProviderToArray($userInfoDataProvider);
            $dataResponse['data'] = $userInfo;
        } catch (\Exception $e) {
            $dataResponse = [
                'success' => false,
                'message' => $e->getMessage(),
            ];
            $status = 500;
        }

        return $this->json($dataResponse, $status)->setEncodingOptions(JSON_UNESCAPED_SLASHES);
    }

    private function convertUserInfoDataProviderToArray(UserInfoDataProvider $userInfoDataProvider): array
    {
        $userInfo = $userInfoDataProvider->toArray();

        $tips = $userInfoDataProvider->getTips();
        $canBeNull = [
            'scoreTeam1', 'scoreTeam2', 'tipTeam1', 'tipTeam2', 'score',
        ];
        foreach ($tips as $tipKey => $tip) {
            foreach ($canBeNull as $methode) {
                $methodeName = 'get' . ucfirst($methode);
                if ($tip->$methodeName() === null) {
                    $userInfo['tips'][$tipKey][$methode] = null;
                }
            }
        }

        return $userInfo;
    }

}
