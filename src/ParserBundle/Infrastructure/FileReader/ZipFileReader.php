<?php

namespace App\ParserBundle\Infrastructure\FileReader;

use App\ParserBundle\Domain\MemeImageCollection;
use App\ParserBundle\Infrastructure\FileUploader\UploadedExportFile;
use App\ParserBundle\Infrastructure\SlackExportParser\SlackExportParser;
use App\ParserBundle\Infrastructure\Shared\Filesystem\FilesystemManager;

class ZipFileReader extends JsonFileReader implements FileReaderInterface
{
  protected $dir;

  public function __construct(
      FilesystemManager $filesystem,
      SlackExportParser $slackExportParser
  ) {
    parent::__construct($filesystem, $slackExportParser);
  }

  public function getUrls(UploadedExportFile $file) : MemeImageCollection
  {
    $dir = $this->filesystem->unZip($file);

    $urls = new MemeImageCollection();
    $files = $this->filesystem->listFiles($dir,"*.json");

    foreach ($files as $file){
      $fileContent = parent::getUrls($file);
      $urls->merge($fileContent);
    }

    return $urls;
  }
}
