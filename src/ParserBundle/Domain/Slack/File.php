<?php

namespace App\ParserBundle\Domain\Slack;

class File
{
    public function __construct(
        public readonly string $urlPrivateDownload
    ) {
    }
}
