<?php

namespace App\ParserBundle\Domain;

interface InputFileReaderInterface
{
    public function getSlackJson(string $filePath, string $type): string;
}
