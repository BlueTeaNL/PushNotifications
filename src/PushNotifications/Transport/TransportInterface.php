<?php

namespace PushNotifications\Transport;

use PushNotifications\Model\MessageInterface;

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
