<?php

namespace Bluetea\PushNotifications\Endpoint;

use Bluetea\PushNotifications\Client\ClientInterface;

abstract class BaseEndpoint implements EndpointInterface
{
    /**
     * @var ClientInterface
     */
    protected $apiClient;

    /**
     * @var string
     */
    protected $appId;

    public function __construct(ClientInterface $apiClient, $appId, $config)
    {
        $this->apiClient    = $apiClient;
        $this->appId        = $appId;
        $this->config       = $config;
    }
}