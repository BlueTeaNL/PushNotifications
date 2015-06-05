<?php

namespace Bluetea\PushNotifications\Client;

use Bluetea\PushNotifications\Authentication\AuthenticationInterface;
use Bluetea\PushNotifications\Request\HttpMethod;

interface ClientInterface
{
    /**
     * Call the endpoint
     *
     * @param $endpoint
     * @param array $endpointParameters
     * @param array $headers
     * @param string $body
     * @param array $json
     * @param string $method
     * @param string $formParams
     * @return mixed
     */
    public function callEndpoint($endpoint, array $endpointParameters = [], array $headers = [], $body = null, array $json= [], $formParams = null, $method = HttpMethod::REQUEST_GET);

    /**
     * @param AuthenticationInterface $authentication
     */
    public function setAuthentication(AuthenticationInterface $authentication);

    /**
     * @return AuthenticationInterface $authentication
     */
    public function getAuthentication();

    /**
     * @param string $baseUrl
     */
    public function setBaseUrl($baseUrl);

    /**
     * @param string $httpMethod
     */
    public function setHttpMethod($httpMethod);

    /**
     * @return mixed
     */
    public function getResult();

    /**
     * @return mixed
     */
    public function getResultHttpCode();
}