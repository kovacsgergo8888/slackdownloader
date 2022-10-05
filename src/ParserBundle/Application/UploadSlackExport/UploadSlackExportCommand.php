<?php

namespace App\ParserBundle\Application\UploadSlackExport;

class UploadSlackExportCommand
{

    public function __construct(
        public readonly string $pathName,
        public readonly string $clientOriginalName
    ) {
    }
}
