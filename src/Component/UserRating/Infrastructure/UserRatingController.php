<?php declare(strict_types=1);

namespace App\Component\UserRating\Infrastructure;

use App\DataTransferObject\RankingInfoEventDataProvider;
use App\DataTransferObject\UserRatingListDataProvider;
use App\Service\Redis\RedisServiceInterface;
use App\Service\RedisKey\RedisKeyService;
use RuntimeException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class UserRatingController extends AbstractController
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
     * @Route("/api/rating", name="rating", methods={"GET"})
     */
    public function userTips(): JsonResponse
    {
        $dataResponse = [
            'success' => true,
        ];
        $status = 200;
        try {
            $userRatingListDataProvider = $this->getTable();
            $dataResponse['data'] = $userRatingListDataProvider->toArray();
        } catch (\Exception $e) {
            $dataResponse = [
                'success' => false,
                'message' => $e->getMessage(),
            ];
            $status = 500;
        }

        return $this->json($dataResponse, $status)->setEncodingOptions(JSON_UNESCAPED_SLASHES);
    }

    /**
     * @return \App\DataTransferObject\RankingInfoEventDataProvider
     */
    private function getTable(): RankingInfoEventDataProvider
    {
        $redisInfo = $this->redisService->get(RedisKeyService::getTable());
//        $redisInfo = file_get_contents(__DIR__ . '/table.json');
        $arrayInfo = json_decode($redisInfo, true);
        if (JSON_ERROR_NONE !== json_last_error()) {
            throw new RuntimeException(\json_last_error_msg(), \json_last_error());
        }

        if (count($arrayInfo) === 0) {
            throw new RuntimeException('Empty data');
        }

        $userRatingListDataProvider = new RankingInfoEventDataProvider();
        $userRatingListDataProvider->fromArray($arrayInfo);

        return $userRatingListDataProvider;
    }
}
