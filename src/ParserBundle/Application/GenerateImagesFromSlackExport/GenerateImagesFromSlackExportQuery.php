<?php

namespace App\ParserBundle\Application\GenerateImagesFromSlackExport;

class GenerateImagesFromSlackExportQuery
{

    public function __construct(
        public readonly string $slackJson,
        public readonly int $workerId
    ) {
    }
}
