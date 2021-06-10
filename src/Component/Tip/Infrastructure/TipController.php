<?php declare(strict_types=1);

namespace App\Component\Tip\Infrastructure;

use App\Component\Tip\Application\MappingTip;
use App\Entity\Tips;
use App\Repository\TipsRepository;
use App\Service\JsonSchemaValidation\JsonSchemaValidationService;
use App\Service\Message\MessageServiceInterface;
use App\Service\Redis\RedisService;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\User\UserInterface;

class TipController extends AbstractController
{
    /**
     * @var \App\Service\JsonSchemaValidation\JsonSchemaValidationService
     */
    private JsonSchemaValidationService $jsonSchemaValidation;

    /**
     * @var \App\Component\Tip\Application\MappingTip
     */
    private MappingTip $mappingTip;
    /**
     * @var \App\Service\Message\MessageServiceInterface
     */
    private MessageServiceInterface $messageService;

    /**
     * @var \App\Repository\TipsRepository
     */
    private TipsRepository $tipsRepository;
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private EntityManagerInterface $entityManager;

    /**
     * @param \App\Service\JsonSchemaValidation\JsonSchemaValidationService $jsonSchemaValidation
     */
    public function __construct(
        JsonSchemaValidationService $jsonSchemaValidation,
        MappingTip $mappingTip,
        MessageServiceInterface $messageService,
        TipsRepository $tipsRepository,
        EntityManagerInterface $entityManager
    )
    {
        $this->jsonSchemaValidation = $jsonSchemaValidation;
        $this->mappingTip = $mappingTip;
        $this->messageService = $messageService;
        $this->tipsRepository = $tipsRepository;
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/api/tip/send", name="tip_send", methods={"POST"})
     */
    public function sendTip(Request $request): JsonResponse
    {
        $content = $this->getContent($request);

        $error = $this->jsonSchemaValidation->getErrors($content, 'tip');

        if (count($error) > 0) {
            $data = [
                'success' => false,
                'message' => $error,
            ];
            return $this->json($data, 422)->setEncodingOptions(JSON_UNESCAPED_SLASHES);
        }

        /** @var \App\Entity\User $user */
        $user = $this->getUser();

        $tipEventDataProvider = $this->mappingTip->map($content, $user);

        $matchId = $tipEventDataProvider->getMatchId();
        $matchIdArray = explode(':',$matchId);
        $matchDate = new \DateTime($matchIdArray[0] . ' ' . $matchIdArray[1]);
        $now = new \DateTime();

        if($matchDate < $now) {
            $data = [
                'success' => false,
                'message' => 'For games in the past you can not type',
            ];
            return $this->json($data, 422)->setEncodingOptions(JSON_UNESCAPED_SLASHES);
        }

        $tipEventDataProvider->setTipDatetime($now->format('Y-m-d H:i'));

        $tip = $this->tipsRepository->getTip($user->getUsername(), $tipEventDataProvider->getMatchId());
        if (!$tip instanceof Tips) {
            $tip = new Tips();
            $tip->setUsername($user->getUsername());
            $tip->setMatchId($matchId);
        }
        $tip->setTipTeam1($tipEventDataProvider->getTipTeam1());
        $tip->setTipTeam2($tipEventDataProvider->getTipTeam2());

        $this->entityManager->persist($tip);
        $this->entityManager->flush();

        $this->messageService->send($tipEventDataProvider);

        return $this->json([
            'success' => true,
        ])->setEncodingOptions(JSON_UNESCAPED_SLASHES);
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
