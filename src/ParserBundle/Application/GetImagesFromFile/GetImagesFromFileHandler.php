<?php

namespace App\ParserBundle\Application\GetImagesFromFile;

use App\ParserBundle\Application\Exception\ApplicationException;
use App\ParserBundle\Domain\Event\DomainEventDispatcherInterface;
use App\ParserBundle\Domain\Event\UserParsedImagesEvent;
use App\ParserBundle\Domain\Exception\DomainException;
use App\ParserBundle\Domain\MemeImageCollection;
use App\ParserBundle\Domain\ShoprenterWorkerRepositoryInterface;
use App\ParserBundle\Infrastructure\FileReader\FileReaderInterface;
use App\ParserBundle\Infrastructure\FileUploader\FileUploaderInterface;
use App\ParserBundle\Infrastructure\FileUploader\TempFile;
use DateTimeImmutable;

class GetImagesFromFileHandler
{
    private DomainEventDispatcherInterface $dispatcher;
    private ShoprenterWorkerRepositoryInterface $workerRepository;
    private FileUploaderInterface $fileUploader;
    private FileReaderInterface $fileReader;

    public function __construct(
        DomainEventDispatcherInterface $dispatcher,
        ShoprenterWorkerRepositoryInterface $workerRepository,
        FileUploaderInterface $fileUploader,
        FileReaderInterface $fileReader
    ) {
        $this->dispatcher = $dispatcher;
        $this->workerRepository = $workerRepository;
        $this->fileUploader = $fileUploader;
        $this->fileReader = $fileReader;
    }

    /**
     * @throws ApplicationException
     */
    public function __invoke(GetImagesFromFileQuery $query): MemeImageCollection
    {
        $worker = $this->workerRepository->getById($query->getWorkerId());

        try {
            $uploadedExportFile = $this->fileUploader->uploadFile(
                new TempFile(
                    $query->getFilePath()
                ),
                $query->getFileName()
            );

            $collection = $this->fileReader->getUrls($uploadedExportFile);
        } catch (DomainException $e) {
            throw new ApplicationException($e->getMessage(), $e->getCode());
        }

        $this->dispatcher->dispatchUserActivityEvent(new UserParsedImagesEvent(
            $worker->getId(),
            new DateTimeImmutable()
        ));

        return $collection;
    }
}
