<?php

namespace DingTalk\Message;

use DingTalk\Robot;

class TextMessage implements MessageInterface
{
    private array $atMobiles = [];
    private array $atUserIds = [];
    private bool  $isAtAll   = false;

    public function __construct(private Robot $robot, private string $content)
    {
    }

    public function at(array $mobiles, array $userIds = []): static
    {
        $this->atMobiles = $mobiles;
        $this->atUserIds = $userIds;
        return $this;
    }

    public function atAll(): static
    {
        $this->isAtAll = true;
        return $this;
    }

    public function send(): array
    {
        return $this->robot->send($this);
    }

    public function toArray(): array
    {
        return [
            'msgtype' => 'text',
            'text'    => ['content' => $this->content],
            'at'      => [
                'atMobiles' => $this->atMobiles,
                'atUserIds' => $this->atUserIds,
                'isAtAll'   => $this->isAtAll,
            ],
        ];
    }
}
