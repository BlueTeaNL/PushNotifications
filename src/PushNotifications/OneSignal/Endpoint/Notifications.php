<?php

namespace Bluetea\PushNotifications\OneSignal\Endpoint;

use Bluetea\PushNotifications\Client\ClientInterface;
use Bluetea\PushNotifications\Endpoint\BaseEndpoint;
use Bluetea\PushNotifications\Endpoint\EndpointInterface;
use Bluetea\PushNotifications\Model\MessageInterface;
use Bluetea\PushNotifications\Request\HttpMethod;

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

        $json = array_merge([
            'app_id' => $this->appId,
            'headings' => ['en' => $message->getTitle()],
            'contents' => ['en' => $message->getContent()],
        ], $config);

        $headers = ['Authorization' => sprintf('Basic %s', $this->restApiKey)];

        return $this->apiClient->callEndpoint(
            self::ENDPOINT,
            [],
            $headers,
            [],
            $json,
            [],
            HttpMethod::REQUEST_POST
        );
    }

    public function viewAllNotifications()
    {
        //        --header "Authorization: Basic NGEwMGZmMjItY2NkNy0xMWUzLTk5ZDUtMDAwYzI5NDBlNjJj" \
        //    https://onesignal.com/api/v1/notifications?app_id={appId}
        $headers = ['Authorization' => sprintf('Basic %s', $this->restApiKey)];

        return $this->apiClient->callEndpoint(
            self::ENDPOINT,
            ['app_id' => $this->appId],
            $headers,
            [],
            [],
            [],
            HttpMethod::REQUEST_GET
        );
    }
}