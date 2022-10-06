<?php

namespace App\ParserBundle\Application\UploadSlackExport;

use App\ParserBundle\Application\Exception\ApplicationException;
use App\ParserBundle\Domain\InputFileReaderInterface;
use Exception;

class UploadSlackExportHandler
{
    public function __construct(
        private readonly InputFileReaderInterface $fileReader
    ) {
    }

    public function __invoke(UploadSlackExportCommand $command)
    {
        try {
            $json = $this->fileReader->getSlackJson($command->filePath, $command->extension);
        } catch (Exception $e) {
            throw new ApplicationException(
                $e->getMessage(),
                $e->getCode()
            );
        }

        return $json;
    }
}
