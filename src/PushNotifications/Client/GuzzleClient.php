<?php

namespace Bluetea\PushNotifications\Client;

use GuzzleHttp\Stream\Stream;
use Bluetea\PushNotifications\Authentication\BasicAuthentication;
use Bluetea\PushNotifications\Exception\ApiException;
use Bluetea\PushNotifications\Exception\HttpNotFoundException;
use Bluetea\PushNotifications\Exception\UnauthorizedException;
use Bluetea\PushNotifications\Request\HttpMethod;
use Bluetea\PushNotifications\Request\StatusCodes;
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
    protected $cookiefile;

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
     * @var string
     */
    protected $formParams;

    /**
     * Call the API with an endpoint
     *
     * @param $endpoint
     * @param array $endpointParameters
     * @param array $headers
     * @param null $body
     * @param array $json
     * @param null $formParams
     * @param string $method
     *
     * @return array
     * @throws ClientException
     * @throws HttpNotFoundException
     * @throws UnauthorizedException
     * @throws \Exception
     */
    public function callEndpoint($endpoint, array $endpointParameters = [], array $headers = [], $body = null, array $json = [], $formParams = null, $method = HttpMethod::REQUEST_GET)
    {
        // Set the endpoint
        $this->setEndpoint($endpoint);
        // Set the parameters
        $this->setEndpointParameters($endpointParameters);
        // Set the headers
        $this->setHeaders($headers);
        // Set form params
        $this->setFormParams($formParams);
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

        // Set cookiefile for sessiondata
        if (!empty($this->cookiefile)) {
            $defaults['config'] = array(
                'curl' => array(
                    CURLOPT_COOKIEJAR => $this->getCookiefile()
                )
            );
        }

        $httpClient = new Client(array(
            'base_url' => $this->getBaseUrl(), 'defaults' => $defaults
        ));

        $this->setHttpClient($httpClient);
    }

    /**
     * Call the API endpoint
     *
     * @throws \Bluetea\PushNotifications\Exception\UnauthorizedException
     * @throws \Bluetea\PushNotifications\Exception\HttpNotFoundException
     * @throws \Bluetea\PushNotifications\Exception\HttpException
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
        if (($json = $this->getJson()) != null) {
            $options['json'] = $json;
            $this->setAccept('application/json');
        }

        if (($formParams = $this->getFormParams()) != null) {
            $options['body'] = $formParams;
            $this->setContentType('application/x-www-form-urlencoded');
        }

        $request = $this->getHttpClient()->createRequest(
            $this->getHttpMethod(),
            $this->getEndpoint(),
            array_merge(
                array('headers' => $this->getHeaders()),
                $options
            )
        );

        foreach ($this->getEndpointParameters() as $key => $value) {
            $request->getQuery()->set($key, $value);
        }

        if (($body = $this->getBody()) != null && (is_null($body))) {
            $request->setBody(Stream::factory($body));
        }

        $response = $this->getHttpClient()->send($request);

        $this->setHttpStatusCode($response->getStatusCode());

        $this->setData($response->json());
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
     * @param string $cookiefile
     */
    public function setCookiefile($cookiefile)
    {
        $this->cookiefile = $cookiefile;
    }

    /**
     * @return string
     */
    public function getCookiefile()
    {
        return $this->cookiefile;
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

    /**
     * @return string
     */
    public function getFormParams()
    {
        return $this->formParams;
    }

    /**
     * @param string $formParams
     */
    public function setFormParams($formParams)
    {
        $this->formParams = $formParams;
    }
}