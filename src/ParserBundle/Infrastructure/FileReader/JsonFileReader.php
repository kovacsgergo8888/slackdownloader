<?php

namespace App\ParserBundle\Infrastructure\FileReader;

use App\ParserBundle\Domain\MemeImageCollection;
use App\ParserBundle\Domain\UploadedFile\UploadedExportFile;
use App\ParserBundle\Infrastructure\Shared\Filesystem\FilesystemManager;

class JsonFileReader
{
    public function getJsonContent(string $filePath): string
    {
        return $this->filesystem->getContents($file);
    }
}
