<?php declare(strict_types=1);

namespace App\Component\UserRating\Infrastructure;

use App\DataTransferObject\RankingInfoEventDataProvider;
use App\DataTransferObject\UserRatingListDataProvider;
use App\Repository\UserRepository;
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
     * @var \App\Repository\UserRepository
     */
    private UserRepository $userRepository;

    /**
     * @param \App\Service\Redis\RedisServiceInterface $redisService
     */
    public function __construct(RedisServiceInterface $redisService, UserRepository $userRepository)
    {
        $this->redisService = $redisService;
        $this->userRepository = $userRepository;
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
            $users = $this->userRepository->findAll();
            $userName2users = [];
            foreach ($users as $user) {
                $userName2users[$user->getUsername()] = $user;
            }
            $userRatingListDataProvider = $this->getTable();

            foreach ($userRatingListDataProvider->getUsers() as $user) {
                $user->setExtraPoint(0);
                $winner = '-';
                $winnerSecret = '-';
                if(isset($userName2users[$user->getName()])) {
                    $winner = $userName2users[$user->getName()]->getTip1();
                    $winnerSecret = $userName2users[$user->getName()]->getTip2();
                }

                $user->setWinner($winner);
                $user->setWinnerSecret($winnerSecret);
            }
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
