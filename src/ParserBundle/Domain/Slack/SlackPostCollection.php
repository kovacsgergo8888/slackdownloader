<?php

namespace App\ParserBundle\Domain\Slack;

class SlackPostCollection
{
    private array $slackPosts = [];

    public function addSlackPost(SlackPost $slackPost): void
    {
        $this->slackPosts[] = $slackPost;
    }

    public function getSlackPosts(): array
    {
        return $this->slackPosts;
    }
}
