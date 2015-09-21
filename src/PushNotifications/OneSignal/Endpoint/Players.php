<?php

namespace Bluetea\PushNotifications\OneSignal\Endpoint;

use Bluetea\PushNotifications\Client\ClientInterface;
use Bluetea\PushNotifications\Endpoint\BaseEndpoint;
use Bluetea\PushNotifications\Endpoint\EndpointInterface;
use Bluetea\PushNotifications\Model\MessageInterface;
use Bluetea\PushNotifications\Request\HttpMethod;
use GuzzleHttp\Exception\ClientException;

class Players extends BaseEndpoint implements EndpointInterface
{
    const ENDPOINT = 'players';

    protected $restApiKey;

    function __construct(ClientInterface $apiClient, $appId, $restApiKey, $oneSignalUserAuthKey, $config)
    {
        parent::__construct($apiClient, $appId, $config);
        $this->restApiKey = $restApiKey;
    }

    public function setKeys($appId, $restApiKey)
    {
        $this->appId = $appId;
        $this->restApiKey = $restApiKey;
    }

    public function viewAllDevices($limit = 50, $offset = 0)
    {
        //https://onesignal.com/api/v1/players?app_id={app_id&}limit={limit}&offset={offset}
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
                'returnMsg' => $e->getMessage()
            ];
        }
    }

    public function viewDevice($id)
    {
        //https://onesignal.com/api/v1/players/{id}
        try {
            return $this->apiClient->callEndpoint(
                sprintf('%s/%s', self::ENDPOINT, $id),
                [],
                [],
                [],
                [],
                [],
                HttpMethod::REQUEST_GET
            );
        } catch (ClientException $e) {
            return [
                'returnMsg' => $e->getMessage()
            ];
        }
    }
}