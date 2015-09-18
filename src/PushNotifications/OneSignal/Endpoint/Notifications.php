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

    public function setKeys($appId, $restApiKey)
    {
        $this->appId = $appId;
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

        if(!isset($config['include_player_ids']) && (empty($config['included_segments'])) && ((!isset($config['tags'])))) {
            $config['included_segments'] = ['All'];
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
            return $this->apiClient->callEndpoint(
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
    }

    public function viewAllNotifications($limit = 50, $offset = 0)
    {
        //https://onesignal.com/api/v1/notifications?app_id={appId}&limit={limit}&offset={offset}
        $headers = ['Authorization' => sprintf('Basic %s', $this->restApiKey)];

        if (!is_int($limit) || $limit > 50 || $limit < 1) {
            $limit = 50;
        }
        if (!is_int($offset) || $offset < 1) {
            $offset = 0;
        }

        try {
            return $this->apiClient->callEndpoint(
                self::ENDPOINT,
                ['app_id' => $this->appId,
                 'limit'  => $limit,
                 'offset' => $offset
                ],
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
    }

    public function viewNotification($id)
    {
        //https://onesignal.com/api/v1/notifications/:id?app_id={appId}
        $headers = ['Authorization' => sprintf('Basic %s', $this->restApiKey)];
        try {
            return $this->apiClient->callEndpoint(
                sprintf('%s/%s', self::ENDPOINT, $id),
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
    }

    public function cancelNotification($id)
    {
        //https://onesignal.com/api/v1/notifications/:id?app_id={appId}
        $headers = ['Authorization' => sprintf('Basic %s', $this->restApiKey)];
        try {
            return $this->apiClient->callEndpoint(
                sprintf('%s/%s', self::ENDPOINT, $id),
                ['app_id' => $this->appId],
                $headers,
                [],
                [],
                [],
                HttpMethod::REQUEST_DELETE
            );
        } catch (ClientException $e) {
            return [
                'returnMsg' => $e->getMessage(),
                'error' => $e->getResponse()->json()['errors'][0]
            ];
        }
    }
}