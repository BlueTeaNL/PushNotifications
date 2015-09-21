<?php

namespace Bluetea\PushNotifications\OneSignal\Endpoint;

use Bluetea\PushNotifications\Client\ClientInterface;
use Bluetea\PushNotifications\Endpoint\BaseEndpoint;
use Bluetea\PushNotifications\Endpoint\EndpointInterface;
use Bluetea\PushNotifications\Model\OneSignalAppInterface;
use Bluetea\PushNotifications\Request\HttpMethod;
use GuzzleHttp\Exception\ClientException;

class Apps extends BaseEndpoint implements EndpointInterface
{
    const ENDPOINT = 'apps';

    protected $oneSignalUserAuthKey;

    function __construct(ClientInterface $apiClient, $appId, $restApiKey, $oneSignalUserAuthKey, $config)
    {
        parent::__construct($apiClient, $appId, $config);
        $this->oneSignalUserAuthKey = $oneSignalUserAuthKey;
    }

    public function setUserAuthKey($oneSignalUserAuthKey)
    {
        $this->oneSignalUserAuthKey = $oneSignalUserAuthKey;
    }

    public function viewAllApps()
    {
        //https://onesignal.com/api/v1/apps
        $headers = ['Authorization' => sprintf('Basic %s', $this->oneSignalUserAuthKey)];

        try {
            return $this->apiClient->callEndpoint(
                self::ENDPOINT,
                [],
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

    public function viewApp($id)
    {
        //https://onesignal.com/api/v1/apps/{id}
        $headers = ['Authorization' => sprintf('Basic %s', $this->oneSignalUserAuthKey)];

        try {
            return $this->apiClient->callEndpoint(
                sprintf('%s/%s', self::ENDPOINT, $id),
                [],
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

    public function createApp(OneSignalAppInterface $app)
    {
        //https://onesignal.com/api/v1/apps
        $headers = ['Authorization' => sprintf('Basic %s', $this->oneSignalUserAuthKey)];

        $json = [
            'name'              => $app->getName(),
            'apns_env'          => $app->getEnv(),
            'apns_p12'          => $app->getP12File(),
            'apns_p12_password' => $app->getP12Password(),
            'gcm_key'           => $app->getGooglePushAutoKey(),
        ];

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
                'returnMsg' => $e->getMessage()
            ];
        }
    }

    public function editApp(OneSignalAppInterface $app)
    {
        //https://onesignal.com/api/v1/apps/{id}
        $headers = ['Authorization' => sprintf('Basic %s', $this->oneSignalUserAuthKey)];

        $json = [
            'name'              => $app->getName(),
            'apns_env'          => $app->getEnv(),
            'apns_p12'          => $app->getP12File(),
            'apns_p12_password' => $app->getP12Password(),
            'gcm_key'           => $app->getGooglePushAutoKey(),
        ];
        try {
            return $this->apiClient->callEndpoint(
                sprintf('%s/%s', self::ENDPOINT, $app->getId()),
                [],
                $headers,
                [],
                $json,
                [],
                HttpMethod::REQUEST_PUT
            );
        } catch (ClientException $e) {
            return [
                'returnMsg' => $e->getMessage()
            ];
        }
    }
}