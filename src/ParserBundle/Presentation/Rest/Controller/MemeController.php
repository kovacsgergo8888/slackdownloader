<?php

namespace App\ParserBundle\Presentation\Rest\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;

class MemeController extends AbstractController
{
    use HandleTrait;

    public function __construct(MessageBusInterface $queryBus)
    {
        $this->messageBus = $queryBus;
    }

    public function generateAction(Request $request): JsonResponse
    {
        $body = $request->getContent();
        $memeCollection = $this->handle(
            new GenerateMemeCollectionCommand($body)
        );

        return new JsonResponse($memeCollection);
    }
}