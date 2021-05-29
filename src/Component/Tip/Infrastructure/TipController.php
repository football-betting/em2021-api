<?php declare(strict_types=1);

namespace App\Component\Tip\Infrastructure;

use App\Component\Tip\Application\MappingTip;
use App\Service\JsonSchemaValidation\JsonSchemaValidationService;
use App\Service\Message\MessageServiceInterface;
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
     * @param \App\Service\JsonSchemaValidation\JsonSchemaValidationService $jsonSchemaValidation
     */
    public function __construct(
        JsonSchemaValidationService $jsonSchemaValidation,
        MappingTip $mappingTip,
        MessageServiceInterface $messageService
    )
    {
        $this->jsonSchemaValidation = $jsonSchemaValidation;
        $this->mappingTip = $mappingTip;
        $this->messageService = $messageService;
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
                'error' => $error,
            ];
            return $this->json($data, 422)->setEncodingOptions(JSON_UNESCAPED_SLASHES);
        }

        $user = $this->getUser();
        if (!$user instanceof UserInterface) {
            $data = [
                'success' => false,
                'error' => 'User is not logging',
            ];
            return $this->json($data, 500)->setEncodingOptions(JSON_UNESCAPED_SLASHES);
        }

        $tipEventDataProvider = $this->mappingTip->map($content, $user);
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
