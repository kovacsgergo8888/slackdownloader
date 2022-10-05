<?php

namespace App\ParserBundle\Application\GenerateImagesFromSlackExport;

use App\ParserBundle\Application\Exception\ApplicationException;
use App\ParserBundle\Domain\Event\DomainEventDispatcherInterface;
use App\ParserBundle\Domain\Event\UserParsedImagesEvent;
use App\ParserBundle\Domain\Exception\DomainException;
use App\ParserBundle\Domain\MemeImageCollection;
use App\ParserBundle\Domain\MemeImageCollectionBuilder;
use App\ParserBundle\Domain\ShoprenterWorkerRepositoryInterface;
use App\ParserBundle\Domain\Slack\SlackPostCollectionBuilder;

use \DateTimeImmutable;

class GenerateImagesFromSlackExportHandler
{
    public function __construct(
        private readonly SlackPostCollectionBuilder $slackPostCollectionBuilder,
        private readonly MemeImageCollectionBuilder $memeImageCollectionBuilder,
        private readonly ShoprenterWorkerRepositoryInterface $workerRepository,
        private readonly DomainEventDispatcherInterface $dispatcher
    ) {
    }

    public function __invoke(GenerateImagesFromSlackExportQuery $query): MemeImageCollection
    {
        try {
            $worker = $this->workerRepository->getById($query->workerId);
            $slackPosts = $this->slackPostCollectionBuilder->build($query->slackJson);
            $memeImageCollection = $this->memeImageCollectionBuilder->build(
                $slackPosts
            );
        } catch (DomainException $e) {
            throw new ApplicationException($e->getMessage(), $e->getCode());
        }

        $this->dispatcher->dispatchUserActivityEvent(new UserParsedImagesEvent(
            $worker->getId(),
            new DateTimeImmutable(),
        ));

        return $memeImageCollection;
    }
}
