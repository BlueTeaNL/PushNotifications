<?php

namespace Bluetea\PushNotifications\Transport;

use Bluetea\PushNotifications\Model\MessageInterface;

interface TransportInterface
{
    /**
     * Send the push notification
     *
     * @param MessageInterface $message
     * @return bool
     */
    public function send(MessageInterface $message);
}
