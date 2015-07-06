<?php

namespace Bluetea\PushNotifications\AppCelerator\Endpoint;

use Bluetea\PushNotifications\Client\ClientInterface;
use Bluetea\PushNotifications\Endpoint\BaseEndpoint;
use Bluetea\PushNotifications\Endpoint\EndpointInterface;
use Bluetea\PushNotifications\Model\MessageInterface;
use Bluetea\PushNotifications\Request\HttpMethod;
use GuzzleHttp\Exception\ClientException;

class Notifications extends BaseEndpoint implements EndpointInterface
{
    const ENDPOINT = 'push_notification';

    protected $restApiKey;

    public function createNotification(MessageInterface $message)
    {
        try {
            $this->createLoginCookie();
        } catch (ClientException $e) {
            return [
                'returnMsg' => $e->getMessage(),
                'error'     => $e->getResponse()->json()['meta']['message']
            ];
        }

        //https://api.cloud.appcelerator.com/v1/push_notification/notify.json?key=<APP_KEY>&pretty_json=true
        $config = $this->config;
        if (!is_null($message->getOptions())) {
            foreach ($message->getOptions() as $key => $option) {
                $config[$key] = $option;
            }
        }

        $title = $message->getTitle();
        if (empty($title) || is_null($title)) {
            $title = '-';
        } else {
            $title = strip_tags($title);
        }
        $content = $message->getContent();
        if (empty($content) || is_null($content)) {
            $content = '-';
        } else {
            $content = strip_tags($content);
        }

        $formParams = [
            "channel" => $config['channel'],
            "payload" => [
                "badge"     => $config['badge'],
                "alert"     => $content,
                "title"     => $title,
                "vibrate"   => $config['vibrate'],
                "sound"     => $config['sound'],
                "icon"      => $config['icon'],
            ],
        ];

        try {
            return $this->apiClient->callEndpoint(
                sprintf('%s/notify.json', self::ENDPOINT),
                ['pretty_json' => true, 'key' => $this->appId],
                [],
                [],
                [],
                $formParams,
                HttpMethod::REQUEST_POST
            );
        } catch (ClientException $e) {
            return [
                'returnMsg' => $e->getMessage(),
                'error'     => $e->getResponse()->json()['meta']['message']
            ];
        }
    }

    public function viewAllNotifications()
    {
        try {
            $this->createLoginCookie();
        } catch (ClientException $e) {
            return [
                'returnMsg' => $e->getMessage(),
                'error'     => $e->getResponse()->json()['meta']['message']
            ];
        }

        //https://api.cloud.appcelerator.com/v1/push_notification/count.json?key=<YOUR_APP_KEY>&pretty_json=true
        try {
            return $this->apiClient->callEndpoint(
                sprintf('%s/count.json', self::ENDPOINT),
                ['pretty_json' => true, 'key' => $this->appId],
                [],
                [],
                [],
                [],
                HttpMethod::REQUEST_GET
            );
        } catch (ClientException $e) {
            return [
                'returnMsg' => $e->getMessage(),
                'error'     => $e->getResponse()->json()['meta']['message']
            ];
        }
    }

    private function createLoginCookie()
    {
        $authentication = $this->apiClient->getAuthentication();

        return $this->apiClient->callEndpoint(
            'users/login.json',
            ['pretty_json' => true, 'key' => $this->appId, 'login' => $authentication->getUsername(), 'password' => $authentication->getPassword()],
            [],
            [],
            [],
            [],
            HttpMethod::REQUEST_POST
        );
    }
}