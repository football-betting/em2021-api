<?php


namespace App\Component\GameUserTips\Infrastructure;


use App\Component\GameUserTips\Application\GameUsersTipInterface;
use App\DataTransferObject\GameUserTipsInfoDataProvider;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class GameUserTipsController extends AbstractController
{
    /**
     * @var \App\Component\GameUserTips\Application\GameUsersTipInterface $gameUsersTip
     */
    private $gameUsersTip;

    public function __construct(GameUsersTipInterface $gameUsersTip){
        $this->gameUsersTip = $gameUsersTip;
    }

    /**
     * @Route("/api/game_tip/past/{matchId}", name="game_tip_past", methods={"GET"})
     */
    public function pastGameUserTips(string $matchId):JsonResponse
    {
        $dataResponse = [
            'success' => true,
        ];

        $status = 200;

        try {
            $gameUserTipsInfoDataProvider = $this->gameUsersTip->getPastGameUsersTip($matchId);
            $gameUserTipsArray = $this->convertToArray($gameUserTipsInfoDataProvider);
            $dataResponse['data'] = $gameUserTipsArray;
        } catch (\Exception $e) {
            $dataResponse = [
                'success' => false,
                'message' => $e->getMessage(),
            ];
            $status = 500;
        }

        return $this->json($dataResponse, $status)->setEncodingOptions(JSON_UNESCAPED_SLASHES);
    }

    private function convertToArray(GameUserTipsInfoDataProvider $gameUserTipsInfoDataProvider): array
    {
        $userInfo = $gameUserTipsInfoDataProvider->toArray();

        $tips = $gameUserTipsInfoDataProvider->getUsersTip();
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