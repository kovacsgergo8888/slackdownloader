<?php


namespace Tests;


use Domain\MemeImage;
use Domain\MemeImageCollection;
use PHPUnit\Framework\TestCase;

class MemeImageCollectionTest extends TestCase
{
  public function testIterate()
  {
    $image = new MemeImage('asd.gif');
    $image2 = new MemeImage('wasd.jpeg');

    $expected = array(
        $image,
        $image2
    );

    $collection = new MemeImageCollection(
        $image,
        $image2
    );

    $result = array();

    foreach ($collection as $image){
      $result[]= $image;
    }

    $this->assertSame($expected,$result);
  }
}