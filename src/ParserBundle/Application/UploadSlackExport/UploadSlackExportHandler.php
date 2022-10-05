<?php

namespace App\ParserBundle\Application\UploadSlackExport;

use App\ParserBundle\Domain\InputFileReaderInterface;


class UploadSlackExportHandler
{
    public function __construct(
        private readonly InputFileReaderInterface $fileReader
    ) {
    }

    public function __invoke(UploadSlackExportCommand $command)
    {
        return $this->fileReader->read(
            $command->pathName,
            $command->clientOriginalName
        );

    }
}
