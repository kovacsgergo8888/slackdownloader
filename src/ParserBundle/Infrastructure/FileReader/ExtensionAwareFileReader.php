<?php

namespace App\ParserBundle\Infrastructure\FileReader;

use App\ParserBundle\Domain\InputFileReaderInterface;

class ExtensionAwareFileReader implements InputFileReaderInterface
{
  private $readers;

  public function addReader($extension, $reader)
  {
    $this->readers[$extension] = $reader;
  }

  public function getSlackJson(string $filePath, string $type): string
  {
      $reader = $this->readers[$type];

      return $reader->getJsonContent($filePath);
  }

}
