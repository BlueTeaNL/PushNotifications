<?php

namespace PushNotifications\Transport;

use PushNotifications\Model\MessageInterface;
use PushNotifications\Authentication\AnonymousAuthentication;
use PushNotifications\Client\GuzzleClient;
use PushNotifications\Request\HttpMethod;

class OneSignalTransport extends AbstractTransport
{
    /**
     * Send the push notification
     *
     * @param MessageInterface $message
     * @return bool
     */
    public function send(MessageInterface $message)
    {
        $baseUrl = 'https://onesignal.com/api/v1/';
        $anonymousAuthentication = new AnonymousAuthentication();
        $apiClient = new GuzzleClient($baseUrl, $anonymousAuthentication);
        $apiClient->setContentType('application/json');
        $apiClient->setAccept('application/json');

        $restApiKey = 'MzYzZjViMmUtMDA1Ni0xMWU1LWI5YjQtYzc2YjI1NDBhMDZl';
        $apiClient->setHeaders(['Authorization' => sprintf('Basic %s', $restApiKey)]);

        $app_id = '363f5aca-0056-11e5-b9b3-b30855d7efb1';
        $json = [
            'app_id' => sprintf('%s', $app_id),
            'included_segments' => ['All'],
            'isAndroid' => 'true',
            'contents' =>  ["en" => "English Message"],
            'headings' => ["en" => "English Title"]
        ];

        $apiClient->callEndpoint('notifications',[], null, $json, HttpMethod::REQUEST_POST);
    }
}
