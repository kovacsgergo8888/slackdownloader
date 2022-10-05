<?php

namespace App\ParserBundle\Domain\Slack;

class SlackPost
{
    /**
     * @var File[]
     */
    private array $files;

    public function __construct(File ...$file){
        $this->files = $file;
    }

    /**
     * @return File[]
     */
    public function getFiles(): array
    {
        return $this->files;
    }
}
