<?php

namespace App\ParserBundle\Domain;

class MemeImage
{
    public function __construct(
        public readonly string $url
    ){}
}
