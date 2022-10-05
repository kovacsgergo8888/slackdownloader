<?php

namespace App\ParserBundle\Domain;

use ArrayAccess;
use ArrayIterator;
use Countable;
use App\ParserBundle\Domain\Exception\DomainException;
use App\ParserBundle\Domain\Slack\SlackPostCollection;
use App\ParserBundle\Domain\Slack\File;
use App\ParserBundle\Domain\Slack\SlackPost;
use IteratorAggregate;
use TypeError;

final class MemeImageCollection implements ArrayAccess, IteratorAggregate, Countable {

  private array $images;

  public function __construct(MemeImage ...$Images) {
    $this->images = $Images;
  }

  public function offsetExists($offset): bool {
    return isset($this->images[$offset]);
  }

  public function offsetGet($offset): MemeImage {
    return $this->images[$offset];
  }

  public function offsetSet($offset,$value): void {

    if ($value instanceof MemeImage) {
      if (is_null($offset)) {
        $this->images[] = $value;
      } else {
        $this->images[$offset] = $value;
      }
    }
    else throw new TypeError("Not a MemeImage!");
  }

  public function offsetUnset($offset): void {
    unset($this->images[$offset]);
  }

  public function getIterator() : ArrayIterator {
    return new ArrayIterator($this->images);
  }

  public function count(): int
  {
    return count($this->images);
  }

  public function merge(MemeImageCollection $collection): void
  {
    foreach ($collection as $c){
      $this->images[] = $c;
    }
  }

    /**
     * @throws DomainException
     */
    public static function createFromArray(array $data): self
    {
        $posts = [];
        foreach ($data as $slackPosts){

            if (!isset($slackPosts['files'])) {
                throw new DomainException('Not correct Slack format!');
            }

            $files = [];
            foreach ($slackPosts['files'] as $f){
                if (isset($f['url_private_download'])){
                    $files[] = new File($f['url_private_download']);
                }
            }
            $posts[] = new SlackPost(...$files);
        }
        $postCollection = new SlackPostCollection(...$posts);
        return self::createFromSlackPostCollection($postCollection);
    }

    public static function createFromSlackPostCollection(SlackPostCollection $slackPosts): self
    {
        $urls = [];
        foreach ($slackPosts->getSlackPosts() as $post) {
          foreach($post->getFiles() as $file) {
            $urls[] = new MemeImage($file->urlPrivateDownload);
          }
        }
        return new self(...$urls);
    }
}
