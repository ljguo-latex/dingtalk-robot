<?php

namespace DingTalk\Message;

use DingTalk\Robot;

class LinkMessage implements MessageInterface
{
    public function __construct(
        private Robot  $robot,
        private string $title,
        private string $text,
        private string $messageUrl,
        private string $picUrl = ''
    ) {
    }

    public function send(): array
    {
        return $this->robot->send($this);
    }

    public function toArray(): array
    {
        return [
            'msgtype' => 'link',
            'link'    => [
                'title'      => $this->title,
                'text'       => $this->text,
                'messageUrl' => $this->messageUrl,
                'picUrl'     => $this->picUrl,
            ],
        ];
    }
}
