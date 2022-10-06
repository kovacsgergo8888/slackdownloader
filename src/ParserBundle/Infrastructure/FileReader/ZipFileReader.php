<?php

namespace App\ParserBundle\Infrastructure\FileReader;

use App\ParserBundle\Infrastructure\Shared\Filesystem\File;
use App\ParserBundle\Infrastructure\Shared\Filesystem\FilesystemInterface;

class ZipFileReader
{
    public function __construct(private readonly FilesystemInterface $filesystem){}

    public function getJsonContent(string $filePath): string
    {
        $file = new File($filePath);
        $dir = $this->filesystem->unZip($file);
        $files = $this->filesystem->listFiles($dir, "*.json");

        $contents = [];
        foreach ($files as $file) {
            $fileContent = $this->filesystem->getContents($file);

            array_merge(
                $contents,
                json_decode($fileContent, true)
            );
        }

        return json_encode($contents);
    }
}
