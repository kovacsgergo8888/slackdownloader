<?php

namespace App\ParserBundle\Infrastructure\FileReader;

use App\ParserBundle\Domain\MemeImageCollection;
use App\ParserBundle\Domain\UploadedFile\UploadedExportFile;
use App\ParserBundle\Infrastructure\Shared\Filesystem\FilesystemManager;

class ZipFileReader extends JsonFileReader
{
  protected $dir;

  public function __construct(FilesystemManager $filesystem)
  {
    parent::__construct($filesystem);
  }

  public function getUrls(UploadedExportFile $file) : MemeImageCollection
  {
    $dir = $this->filesystem->unZip($file);

    $urls = new MemeImageCollection();
    $files = $this->filesystem->listFiles($dir,"*.json");

    foreach ($files as $file){
      $u = parent::getUrls($file);
      $urls->merge($u);
    }

    return $urls;
  }
}
