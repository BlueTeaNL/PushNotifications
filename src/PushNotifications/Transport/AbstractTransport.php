<?php

namespace PushNotifications\Transport;

use PushNotifications\Model\MessageInterface;

abstract class AbstractTransport implements TransportInterface
{
    /**
     * Send the push notification
     *
     * @param MessageInterface $message
     * @return bool
     */
    abstract public function send(MessageInterface $message);
}
