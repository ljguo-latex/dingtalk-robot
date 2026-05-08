<?php

namespace DingTalk\Message;

use DingTalk\Robot;

class ActionCardMessage implements MessageInterface
{
    private string $singleTitle = '';
    private string $singleUrl   = '';
    private array  $btns        = [];

    public function __construct(
        private Robot  $robot,
        private string $title,
        private string $text,
        private string $btnOrientation = '0'
    ) {
    }

    public function single(string $title, string $url): static
    {
        $this->singleTitle = $title;
        $this->singleUrl   = $url;
        return $this;
    }

    public function addButton(string $title, string $actionUrl): static
    {
        $this->btns[] = ['title' => $title, 'actionURL' => $actionUrl];
        return $this;
    }

    public function horizontal(): static
    {
        $this->btnOrientation = '1';
        return $this;
    }

    public function send(): array
    {
        return $this->robot->send($this);
    }

    public function toArray(): array
    {
        $card = [
            'title'          => $this->title,
            'text'           => $this->text,
            'btnOrientation' => $this->btnOrientation,
        ];

        if ($this->singleTitle !== '') {
            $card['singleTitle'] = $this->singleTitle;
            $card['singleURL']   = $this->singleUrl;
        } else {
            $card['btns'] = $this->btns;
        }

        return ['msgtype' => 'actionCard', 'actionCard' => $card];
    }
}
