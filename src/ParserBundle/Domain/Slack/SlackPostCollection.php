<?php

namespace App\ParserBundle\Domain\Slack;

class SlackPostCollection
{
    private array $slackPosts;

    public function __construct(SlackPost ...$slackPosts)
    {
        $this->slackPosts = $slackPosts;
    }

    /** @return SlackPost[] */
    public function getSlackPosts(): array
    {
        return $this->slackPosts;
    }
}
