<?php

namespace App\ParserBundle\Presentation\Rest\Controller;

use App\ParserBundle\Application\GenerateImagesFromSlackExport\GenerateImagesFromSlackExportQuery;
use App\ParserBundle\Application\AuthenticateShoprenterWorker\AuthenticateShoprenterWorkerQuery;
use App\ParserBundle\Application\Exception\ApplicationException;
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
        $authorizationEncoded = $request->headers->get('Authorization');

//        list($user, $password) = explode(':', base64_decode($authorizationEncoded));
//        var_dump($user, $password);die;

        $authorization = array_combine(
            ['user', 'password'],
            explode(':', base64_decode(str_replace('Basic ', '', $authorizationEncoded)))
        );
        try {
            $worker = $this->handle(new AuthenticateShoprenterWorkerQuery(
                $authorization['user'],
                $authorization['password']
            ));
        } catch (ApplicationException $e) {
            return new JsonResponse([], 401);
        }

        $body = $request->getContent();

        $memeCollection = $this->handle(new GenerateImagesFromSlackExportQuery($body, $worker->getId()));

        return new JsonResponse($memeCollection);
    }
}
