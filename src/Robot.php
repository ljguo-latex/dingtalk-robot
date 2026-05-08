<?php

namespace DingTalk;

use DingTalk\Exception\DingTalkException;
use DingTalk\Message\MessageInterface;
use DingTalk\Message\TextMessage;
use DingTalk\Message\LinkMessage;
use DingTalk\Message\MarkdownMessage;
use DingTalk\Message\ActionCardMessage;
use DingTalk\Message\FeedCardMessage;

class Robot
{
    private const API_ENDPOINT = 'https://oapi.dingtalk.com/robot/send';

    private string  $accessToken;
    private ?string $secret;

    public function __construct(string $accessToken, ?string $secret = null)
    {
        $this->accessToken = $accessToken;
        $this->secret      = $secret;
    }

    public function text(string $content): TextMessage
    {
        return new TextMessage($this, $content);
    }

    public function link(string $title, string $text, string $messageUrl, string $picUrl = ''): LinkMessage
    {
        return new LinkMessage($this, $title, $text, $messageUrl, $picUrl);
    }

    public function markdown(string $title, string $text): MarkdownMessage
    {
        return new MarkdownMessage($this, $title, $text);
    }

    public function actionCard(string $title, string $text, string $btnOrientation = '0'): ActionCardMessage
    {
        return new ActionCardMessage($this, $title, $text, $btnOrientation);
    }

    public function feedCard(): FeedCardMessage
    {
        return new FeedCardMessage($this);
    }

    /**
     * @throws DingTalkException
     */
    public function send(MessageInterface $message): array
    {
        $url      = $this->buildUrl();
        $payload  = json_encode($message->toArray(), JSON_UNESCAPED_UNICODE);
        $response = $this->post($url, $payload);

        $result = json_decode($response, true);
        if (!is_array($result)) {
            throw new DingTalkException('Invalid response: ' . $response);
        }

        if (($result['errcode'] ?? 0) !== 0) {
            throw new DingTalkException(
                sprintf('DingTalk error %d: %s', $result['errcode'], $result['errmsg'] ?? '')
            );
        }

        return $result;
    }

    private function buildUrl(): string
    {
        $params = ['access_token' => $this->accessToken];

        if ($this->secret !== null) {
            $timestamp             = (string) (int) (microtime(true) * 1000);
            $params['timestamp']   = $timestamp;
            $params['sign']        = $this->sign($timestamp);
        }

        return self::API_ENDPOINT . '?' . http_build_query($params);
    }

    private function sign(string $timestamp): string
    {
        $str  = $timestamp . "\n" . $this->secret;
        $hmac = hash_hmac('sha256', $str, $this->secret, true);
        return urlencode(base64_encode($hmac));
    }

    private function post(string $url, string $body): string
    {
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => $body,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT        => 10,
            CURLOPT_HTTPHEADER     => ['Content-Type: application/json; charset=utf-8'],
        ]);

        $response = curl_exec($ch);
        $error    = curl_error($ch);
        curl_close($ch);

        if ($response === false) {
            throw new DingTalkException('cURL error: ' . $error);
        }

        return $response;
    }
}
