<?php

namespace App\ParserBundle\Infrastructure\FileReader;

class JsonFileReader
{
    public function getJsonContent(string $filePath): string
    {
        return file_get_contents($filePath);
    }
}
