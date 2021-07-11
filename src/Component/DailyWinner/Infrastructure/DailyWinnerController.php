<?php declare(strict_types=1);

namespace App\Component\DailyWinner\Infrastructure;

use App\DataTransferObject\DailyWinnerListDataProvider;
use App\Service\Redis\RedisServiceInterface;
use App\Service\RedisKey\RedisKeyService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class DailyWinnerController extends AbstractController
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
     * @Route("/api/daily-winners", name="daily_winners", methods={"GET"})
     */
    public function getDailyWinners(): JsonResponse
    {
        $dataResponse = [
            'success' => true,
        ];

        $status = 200;

        try {
            $dailyWinnerListJson = $this->redisService->get(RedisKeyService::getDailyWinner());
            $dailyWinnerListJsonArray = json_decode($dailyWinnerListJson, true);

            $dataResponse['data'] = $dailyWinnerListJsonArray['data'];
        } catch (\Exception $e) {
            $dataResponse = [
                'success' => false,
                'message' => $e->getMessage(),
            ];
            $status = 404;
        }

        return $this->json($dataResponse, $status)->setEncodingOptions(JSON_UNESCAPED_SLASHES);
    }
}
