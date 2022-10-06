<?php

namespace App\ParserBundle\Infrastructure\Shared\Filesystem;

interface FilesystemInterface
{
    public function getContents(File $path): string;

    public function uploadFile(File $file, $uploadName): File;

    public function globRecursive(string $base, string $pattern, int $flags = 0): array;

    public function unZip(File $file): string;

    public function listFiles(string $dir, string $pattern): array;
}
