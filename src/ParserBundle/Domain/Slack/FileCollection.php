<?php

namespace App\ParserBundle\Domain\Slack;

class FileCollection
{
    private array $files = [];

    public function addFile(File $file): void
    {
        $this->files[] = $file;
    }

    public function getFiles(): array
    {
        return $this->files;
    }
}
