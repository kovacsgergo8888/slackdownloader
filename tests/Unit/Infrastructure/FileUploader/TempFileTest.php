<?php

namespace App\Tests\Unit\Infrastructure\FileUploader;

use App\Infrastructure\FileUploader\TempFile;
use PHPUnit\Framework\TestCase;

class TempFileTest extends TestCase
{
  public function testGetName()
  {
    $file = new TempFile(__DIR__ . '/test.json');
    $this->assertEquals('test.json', $file->getName());
  }
}