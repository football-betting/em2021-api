<?php declare(strict_types=1);

namespace App\Component\GameUserTips\Infrastructure;

use App\DataTransferObject\GameUserTipsInfoDataProvider;
use App\Service\Redis\RedisServiceInterface;
use App\Service\RedisKey\RedisKeyService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class GameUserTipsController extends AbstractController
{
    private RedisServiceInterface $redisService;

    /**
     * @param \App\Service\Redis\RedisServiceInterface $redisService
     */
    public function __construct(RedisServiceInterface $redisService)
    {
        $this->redisService = $redisService;
    }

    /**
     * @Route("/api/game_tip/past/{matchId}", name="game_tip_past", methods={"GET"})
     */
    public function pastGameUserTips(string $matchId): JsonResponse
    {
        $dataResponse = [
            'success' => true,
        ];

        $status = 200;

        try {
            $gameUsersTipDataProviderJson = $this->redisService->get(RedisKeyService::getGameUsersTip($matchId));
            $gameUsersTipDataProviderJsonArray = json_decode($gameUsersTipDataProviderJson, true);

            if (empty($gameUsersTipDataProviderJson)) {
                throw new \RuntimeException($matchId. ' no found');
            }

            $gameUserTipsInfoDataProvider = new GameUserTipsInfoDataProvider();
            $gameUserTipsInfoDataProvider->fromArray($gameUsersTipDataProviderJsonArray);

            $gameUserTipsArray = $this->convertToArray($gameUserTipsInfoDataProvider);
            $dataResponse['data'] = $gameUserTipsArray;
        } catch (\Exception $e) {
            $dataResponse = [
                'success' => false,
                'message' => $e->getMessage(),
            ];
            $status = 404;
        }

        return $this->json($dataResponse, $status)->setEncodingOptions(JSON_UNESCAPED_SLASHES);
    }

    private function convertToArray(GameUserTipsInfoDataProvider $gameUserTipsInfoDataProvider): array
    {
        $userInfo = $gameUserTipsInfoDataProvider->toArray();

        $tips = $gameUserTipsInfoDataProvider->getUsersTip();
        $canBeNull = [
            'tipTeam1', 'tipTeam2', 'score',
        ];
        foreach ($tips as $tipKey => $tip) {
            foreach ($canBeNull as $methode) {
                $methodeName = 'get' . ucfirst($methode);
                if ($tip->$methodeName() === null) {
                    $userInfo['usersTip'][$tipKey][$methode] = null;
                }
            }
        }

        return $userInfo;
    }
}
