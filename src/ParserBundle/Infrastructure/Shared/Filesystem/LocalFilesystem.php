<?php

namespace App\ParserBundle\Infrastructure\Shared\Filesystem;

use App\ParserBundle\Domain\Exception\DomainException;
use ZipArchive;

use function copy;

class LocalFilesystem implements FilesystemInterface
{
    private string $uploadDir;

    public function __construct(string $uploadDir)
    {
        $this->uploadDir = $uploadDir;
    }

    public function getContents(File $file): string
    {
        return file_get_contents($file->getPath());
    }

    public function uploadFile(File $file,$uploadName): File
    {
        $upload = $this->uploadDir . $uploadName;

        if (!copy($file->getPath(), $upload)) {
            throw new DomainException('File upload is failed:' . $file->getName());
        }

        return new File($upload);
    }

    public function unZip(File $file): string
    {
        $dir = $this->uploadDir . time();

        $zip = new ZipArchive;
        $zip->open($file->getPath());

        $zip->extractTo($dir);
        $zip->close();

        return $dir;
    }

    public function globRecursive(string $base, string $pattern, $flags = 0): array
    {
        $files = $this->doGlobRecursive($base, $pattern, $flags );
        $result = [];

        foreach ($files as $file) {
            $result[] = new File($file);
        }

        return $result;
    }

    private function doGlobRecursive($base, $pattern, $flags = 0)
    {
        $flags = $flags & ~GLOB_NOCHECK;

        if (substr($base, -1) !== DIRECTORY_SEPARATOR) {
            $base .= DIRECTORY_SEPARATOR;
        }

        $files = glob($base.$pattern, $flags);

        if (!is_array($files)) {
            $files = [];
        }

        $dirs = glob($base.'*', GLOB_ONLYDIR|GLOB_NOSORT|GLOB_MARK);

        if (!is_array($dirs)) {
            return $files;
        }

        foreach ($dirs as $dir) {
            $dirFiles = $this->doGlobRecursive($dir, $pattern, $flags);
            $files = array_merge($files, $dirFiles);
        }

        return $files;
    }

    /**
     * @return File[]
     */
    public function listFiles(string $dir, string $pattern): array
    {
        $files = [];
        foreach ($this->globRecursive($dir, $pattern) as $f) {
            $files[] = new File($f->getPath());
        }

        return $files;
    }
}
