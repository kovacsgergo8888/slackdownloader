<?php

namespace  App\ParserBundle\Infrastructure\SlackExportParser;

use App\ParserBundle\Domain\Exception\DomainException;
use App\ParserBundle\Domain\Slack\File;
use App\ParserBundle\Domain\Slack\SlackPost;
use App\ParserBundle\Domain\Slack\SlackPostCollection;

class SlackExportParser
{
    private const KEY_FILES = 'files';
    private const KEY_URL_PRIVATE_DOWNLOAD = 'url_private_download';

    /**
     * @throws DomainException
     */
    public function parseJson(string $json): SlackPostCollection
    {
        $content = json_decode($json, true);

        if (empty($content)) {
            throw new DomainException('The parsed json content is empty or incorrectly structured!');
        }

        return $this->parseArray($content);
    }

    /**
     * @throws DomainException
     */
    public function parseArray(array $data): SlackPostCollection
    {
        $posts = [];

        foreach ($data as $slackPosts){
            if (!isset($slackPosts[self::KEY_FILES])) {
                throw new DomainException('Incorrect Slack export content format!');
            }

            $files = [];
            foreach ($slackPosts[self::KEY_FILES] as $f){
                if (isset($f[self::KEY_URL_PRIVATE_DOWNLOAD])){
                    $files[] = new File($f[self::KEY_URL_PRIVATE_DOWNLOAD]);
                }
            }
            $posts[] = new SlackPost(...$files);
        }
        return new SlackPostCollection(...$posts);
    }
}
