<?php

namespace App\ParserBundle\Domain;

interface InputFileReaderInterface
{
    public function getContents(): string;
}
