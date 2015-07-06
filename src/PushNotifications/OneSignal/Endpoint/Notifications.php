<?php

namespace Bluetea\PushNotifications\OneSignal\Endpoint;

use Bluetea\PushNotifications\Client\ClientInterface;
use Bluetea\PushNotifications\Endpoint\BaseEndpoint;
use Bluetea\PushNotifications\Endpoint\EndpointInterface;
use Bluetea\PushNotifications\Model\MessageInterface;
use Bluetea\PushNotifications\Request\HttpMethod;
use GuzzleHttp\Exception\ClientException;

class Notifications extends BaseEndpoint implements EndpointInterface
{
    const ENDPOINT = 'notifications';

    protected $restApiKey;

    function __construct(ClientInterface $apiClient, $appId, $restApiKey, $config)
    {
        parent::__construct($apiClient, $appId, $config);
        $this->restApiKey = $restApiKey;
    }

    public function createNotification(MessageInterface $message)
    {
        //https://onesignal.com/api/v1/notifications
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

        $json = array_merge( [
            'app_id'    => $this->appId,
            'headings'  => ['en' => $title],
            'contents'  => ['en' => $content]
        ],
            $config
        );

        $headers = ['Authorization' => sprintf('Basic %s', $this->restApiKey)];

        try {
            $this->apiClient->callEndpoint(
                self::ENDPOINT,
                [],
                $headers,
                [],
                $json,
                [],
                HttpMethod::REQUEST_POST
            );

        } catch (ClientException $e) {
            return [
                'returnMsg' => $e->getMessage(),
                'error' => $e->getResponse()->json()['errors'][0]
            ];
        }

        return null;
    }

    public function viewAllNotifications()
    {
        //https://onesignal.com/api/v1/notifications?app_id={appId}
        $headers = ['Authorization' => sprintf('Basic %s', $this->restApiKey)];

        try {
            $this->apiClient->callEndpoint(
                self::ENDPOINT,
                ['app_id' => $this->appId],
                $headers,
                [],
                [],
                [],
                HttpMethod::REQUEST_GET
            );
        } catch (ClientException $e) {
            return [
                'returnMsg' => $e->getMessage(),
                'error' => $e->getResponse()->json()['errors'][0]
            ];
        }

        return null;
    }
}