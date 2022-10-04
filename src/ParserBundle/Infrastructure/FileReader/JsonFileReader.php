<?php

namespace App\ParserBundle\Infrastructure\FileReader;

use App\ParserBundle\Domain\MemeImageCollection;
use App\ParserBundle\Infrastructure\FileUploader\UploadedExportFile;
use App\ParserBundle\Infrastructure\MemeImageParser\SlackExportParser;
use App\ParserBundle\Infrastructure\Shared\Filesystem\FilesystemManager;

class JsonFileReader implements FileReaderInterface
{
    protected FilesystemManager $filesystem;

    protected SlackExportParser $slackExportParser;

    public function __construct(FilesystemManager $filesystem, SlackExportParser $slackExportParser)
    {
        $this->filesystem = $filesystem;
        $this->slackExportParser = $slackExportParser;
    }

    public function getUrls(UploadedExportFile $file) : MemeImageCollection
    {
        $json = $this->filesystem->getContents($file);

        return $this->slackExportParser->parseJson($json);
    }
}
