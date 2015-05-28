<?php

namespace Bluetea\PushNotifications\Transport;

use Bluetea\PushNotifications\Model\MessageInterface;

abstract class AbstractTransport implements HandlerInterface
{
    /**
     * Send the push notification
     *
     * @param MessageInterface $message
     * @return bool
     */
    abstract public function send(MessageInterface $message);
}
