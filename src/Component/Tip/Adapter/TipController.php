<?php declare(strict_types=1);

namespace App\Component\Tip\Adapter;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TipController extends AbstractController
{
    /**
     * ToDo add user - user should be load from session
     *
     * @Route("/tip/send", name="tip_send", methods={"POST"})
     */
    public function sendTip(Request $request): JsonResponse
    {
        $content = $request->getContent();

        $data = ['error' => true];

        return $this->json($data)->setEncodingOptions(JSON_UNESCAPED_SLASHES);
    }
}
