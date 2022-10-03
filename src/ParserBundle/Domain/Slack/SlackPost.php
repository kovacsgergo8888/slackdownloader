<?php

namespace App\ParserBundle\Domain\Slack;

class SlackPost
{
    public function __construct(
        public readonly string $files
    ){}
}
