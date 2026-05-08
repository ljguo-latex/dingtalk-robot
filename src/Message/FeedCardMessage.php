<?php

namespace DingTalk\Message;

use DingTalk\Robot;

class FeedCardMessage implements MessageInterface
{
    private array $links = [];

    public function __construct(private Robot $robot)
    {
    }

    public function addLink(string $title, string $messageUrl, string $picUrl = ''): static
    {
        $this->links[] = [
            'title'      => $title,
            'messageURL' => $messageUrl,
            'picURL'     => $picUrl,
        ];
        return $this;
    }

    public function send(): array
    {
        return $this->robot->send($this);
    }

    public function toArray(): array
    {
        return [
            'msgtype'  => 'feedCard',
            'feedCard' => ['links' => $this->links],
        ];
    }
}
