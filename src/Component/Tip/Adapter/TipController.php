<?php declare(strict_types=1);

namespace App\Component\Tip\Adapter;

use App\Service\JsonSchemaValidation\JsonSchemaValidationService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TipController extends AbstractController
{

    private JsonSchemaValidationService $jsonSchemaValidation;

    /**
     * @param \App\Service\JsonSchemaValidation\JsonSchemaValidationService $jsonSchemaValidation
     */
    public function __construct(JsonSchemaValidationService $jsonSchemaValidation)
    {
        $this->jsonSchemaValidation = $jsonSchemaValidation;
    }

    /**
     * ToDo add user - user should be load from session
     *
     * @Route("/tip/send", name="tip_send", methods={"POST"})
     */
    public function sendTip(Request $request): JsonResponse
    {
        $content = $request->getContent();

        $error = $this->jsonSchemaValidation->getErrors($content, 'tip');
        if (count($error) > 0) {
            $data = [
                'success' => false,
                'error' => $error
            ];
            return $this->json($data, 422)->setEncodingOptions(JSON_UNESCAPED_SLASHES);
        }


        return $this->json($data)->setEncodingOptions(JSON_UNESCAPED_SLASHES);
    }
}
