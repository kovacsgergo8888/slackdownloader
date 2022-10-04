<?php

namespace  App\ParserBundle\Infrastructure\MemeImageParser;

use App\ParserBundle\Domain\Exception\DomainException;
use App\ParserBundle\Domain\MemeImage;
use App\ParserBundle\Domain\MemeImageCollection;

class SlackExportParser
{
    private const KEY_FILES = 'files';
    private const KEY_URL_PRIVATE_DOWNLOAD = 'url_private_download';

    public function parseJson(string $json): MemeImageCollection
    {
        $content = json_decode($json, true);

        if (empty($content)) {
            throw new DomainException('The parsed Slack export content is empty!');
        }

        $urls = [];
        foreach ($content as $slackPosts){
            if (!isset($slackPosts[self::KEY_FILES])) {
                throw new DomainException('Incorrect Slack export content format!');
            }

            foreach ($slackPosts[self::KEY_FILES] as $file){
                if (isset($file[self::KEY_URL_PRIVATE_DOWNLOAD])){
                    $urls[] = new MemeImage($file[self::KEY_URL_PRIVATE_DOWNLOAD]);
                }
            }
        }

        return new MemeImageCollection(...$urls);
    }
}
