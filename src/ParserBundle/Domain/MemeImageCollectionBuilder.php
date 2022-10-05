<?php

namespace App\ParserBundle\Domain;

use App\ParserBundle\Domain\Slack\SlackPostCollection;

class MemeImageCollectionBuilder
{
    public function build(SlackPostCollection $slackPosts): MemeImageCollection
    {
        $urls = [];
        foreach ($slackPosts->getSlackPosts() as $post) {
            foreach ($post->getFiles() as $file) {
                $urls[] = new MemeImage($file->urlPrivateDownload);
            }
        }
        return new MemeImageCollection(...$urls);
    }
}
