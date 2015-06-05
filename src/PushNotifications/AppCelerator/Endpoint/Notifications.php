<?php

namespace Bluetea\PushNotifications\AppCelerator\Endpoint;

use Bluetea\PushNotifications\Client\ClientInterface;
use Bluetea\PushNotifications\Endpoint\BaseEndpoint;
use Bluetea\PushNotifications\Endpoint\EndpointInterface;
use Bluetea\PushNotifications\Model\MessageInterface;
use Bluetea\PushNotifications\Request\HttpMethod;

class Notifications extends BaseEndpoint implements EndpointInterface
{
    const ENDPOINT = 'push_notification';

    protected $restApiKey;

    public function createNotification(MessageInterface $message)
    {
        $this->createLoginCookie();

        //https://api.cloud.appcelerator.com/v1/push_notification/notify.json?key=<APP_KEY>&pretty_json=true
        $config = $this->config;
        if (!is_null($message->getOptions())) {
            foreach ($message->getOptions() as $key => $option) {
                $config[$key] = $option;
            }
        }

        $formParams = [
            "channel" => $config['channel'],
            "payload" => [
                "badge"     => $config['badge'],
                "alert"     => $message->getContent(),
                "title"     => $message->getTitle(),
                "vibrate"   => $config['vibrate'],
                "sound"     => $config['sound'],
                "icon"      => $config['icon'],
            ],
        ];

        return $this->apiClient->callEndpoint(
            sprintf('%s/notify.json', self::ENDPOINT),
            ['pretty_json' => true, 'key' => $this->appId],
            [],
            [],
            [],
            $formParams,
            HttpMethod::REQUEST_POST
        );
    }

    public function viewAllNotifications()
    {
        $this->createLoginCookie();

        //https://api.cloud.appcelerator.com/v1/push_notification/count.json?key=<YOUR_APP_KEY>&pretty_json=true
        return $this->apiClient->callEndpoint(
            sprintf('%s/count.json', self::ENDPOINT),
            ['pretty_json' => true, 'key' => $this->appId],
            [],
            [],
            [],
            [],
            HttpMethod::REQUEST_GET
        );
    }

    private function createLoginCookie()
    {
        $authentication = $this->apiClient->getAuthentication();

        $this->apiClient->callEndpoint(
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