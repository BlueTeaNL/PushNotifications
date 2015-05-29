<?php

namespace PushNotifications\Client;

use GuzzleHttp\Stream\Stream;
use PushNotifications\Authentication\BasicAuthentication;
use PushNotifications\Exception\ApiException;
use PushNotifications\Exception\HttpNotFoundException;
use PushNotifications\Exception\UnauthorizedException;
use PushNotifications\Request\HttpMethod;
use PushNotifications\Request\StatusCodes;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;

class GuzzleClient extends BaseClient implements ClientInterface
{
    /**
     * @var Client
     */
    protected $httpClient;

    /**
     * @var string
     */
    protected $crtBundleFile;

    /**
     * @var int
     */
    protected $httpStatusCode;

    /**
     * @var []
     */
    protected $json;

    /**
     * Call the API with an endpoint
     *
     * @param $endpoint
     * @param array $endpointParameters
     * @param null $body
     * @param string $method
     *
     * @return array
     * @throws HttpNotFoundException
     * @throws UnauthorizedException
     */
    public function callEndpoint($endpoint, array $endpointParameters = [], $body = null, $json = [], $method = HttpMethod::REQUEST_GET)
    {
        // Set the endpoint
        $this->setEndpoint($endpoint);
        // Set the parameters
        $this->setEndpointParameters($endpointParameters);
        // Set the body
        $this->setBody($body);
        // Set the json
        $this->setJson($json);
        // Set the HTTP method
        $this->setHttpMethod($method);
        // Call the endpoint
        $this->call();
        // return the result
        return $this->getResult();
    }

    /**
     * This method initializes a Guzzle Http client instance and saves it in the private $httpClient variable.
     * Other methods can retrieve this Guzzle Http client instance by calling $this->getHttpClient().
     * This method should called only once
     *
     * @throws ApiException
     */
    public function init()
    {
        // Check if the curl object isn't set already
        if ($this->isInit()) {
            throw new ApiException("A Guzzle Http Client instance is already initialized");
        }

        $defaults = array(
            'version' => '1.0'
        );

        // Enable debug if debug is true
        if ($this->isDebug()) {
            $defaults['debug'] = true;
        }

        // Set crtBundleFile (certificate) if given else disable SSL verification
        if (!empty($this->crtBundleFile)) {
            $defaults['verify'] = $this->getCrtBundleFile();
        }

        $httpClient = new Client(array(
            'base_url' => $this->getBaseUrl(), 'defaults' => $defaults
        ));

        $this->setHttpClient($httpClient);
    }

    /**
     * Call the API endpoint
     *
     * @throws \PushNotifications\Exception\UnauthorizedException
     * @throws \PushNotifications\Exception\HttpNotFoundException
     * @throws \PushNotifications\Exception\HttpException
     */
    protected function call()
    {
        // Check if the curl object is set
        if (!$this->isInit()) {
            // If it isn't, we do it right now
            $this->init();
        }

        $options = array();
        // Set basic authentication if enabled
        if ($this->authentication instanceof BasicAuthentication) {
            $options['auth'] = array(
                $this->authentication->getUsername(),
                $this->authentication->getPassword()
            );
        }

        // Set json
        if (($json = $this->getJson()) !== null) {
            $options['json'] = $json;
        }

        $request = $this->getHttpClient()->createRequest(
            $this->getHttpMethod(),
            $this->getEndpoint(),
            array_merge(
                $options,
                array('headers' => $this->getHeaders())
            )
        );

        foreach ($this->getEndpointParameters() as $key => $value) {
            $request->getQuery()->set($key, $value);
        }

        if (($body = $this->getBody()) !== null) {
            $request->setBody(Stream::factory($body));
        }

        try {
            $response = $this->getHttpClient()->send($request);
        } catch (ClientException $e) {// Check if not found
            if ($e->getResponse()->getStatusCode() === StatusCodes::HTTP_NOT_FOUND) {
                throw new HttpNotFoundException();
            }

            // Check if unauthorized
            if ($e->getResponse()->getStatusCode() === StatusCodes::HTTP_UNAUTHORIZED) {
                throw new UnauthorizedException();
            }

            throw $e;
        }

        $this->setHttpStatusCode($response->getStatusCode());

        if ($this->getAccept() == 'application/json') {
            $this->setData($response->json());
        } else {
            $this->setData($response->getBody(true));
        }
    }

    /**
     * @param Client $httpClient
     */
    public function setHttpClient($httpClient)
    {
        $this->httpClient = $httpClient;
    }

    /**
     * @return Client
     */
    public function getHttpClient()
    {
        return $this->httpClient;
    }

    /**
     * @param string $crtBundleFile
     */
    public function setCrtBundleFile($crtBundleFile)
    {
        $this->crtBundleFile = $crtBundleFile;
    }

    /**
     * @return string
     */
    public function getCrtBundleFile()
    {
        return $this->crtBundleFile;
    }

    /**
     * @return int
     */
    public function getResultHttpCode()
    {
        return $this->httpStatusCode;
    }

    /**
     * @param int $httpStatusCode
     */
    public function setHttpStatusCode($httpStatusCode)
    {
        $this->httpStatusCode = $httpStatusCode;
    }

    /**
     * Returns if Curl is initialized or not
     *
     * @return bool
     */
    protected function isInit()
    {
        if ($this->getHttpClient() != null) {
            return true;
        }
        return false;
    }

    /**
     * @return mixed
     */
    public function getJson()
    {
        return $this->json;
    }

    /**
     * @param mixed $json
     */
    public function setJson($json)
    {
        $this->json = $json;
    }
}