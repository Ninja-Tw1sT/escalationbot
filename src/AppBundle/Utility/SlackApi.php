<?php

namespace AppBundle\Utility;

use Exception;

class SlackApi
{
    private $domain = 'https://slack.com/api';

    private $icon_emoji;

    private $token;

    private $username;

    public function __construct($token, $username, $icon_emoji)
    {
        $this->token = $token;
        $this->username = $username;
        $this->icon_emoji = $icon_emoji;
    }

    public function call($url, $data = [])
    {
        $data['token'] = $this->token;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
        curl_setopt($ch, CURLOPT_URL, $this->domain . $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        $bodyRaw = curl_exec($ch);
        $body = json_decode($bodyRaw, true);

        return $body;
    }

    public function postMessageToChannel($messageData)
    {
        // Add features to chat message
        $messageData['as_user'] = false;

        if (!isset($messageData['username'])) {
            $messageData['username'] = $this->username;
        }

        if (!isset($messageData['icon_emoji'])) {
            $messageData['icon_emoji'] = $this->icon_emoji;
        }

        return $this->call('/chat.postMessage', $messageData);
    }
}
