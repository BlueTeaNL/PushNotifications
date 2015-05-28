<?php

namespace Bluetea\PushNotifications\Transport;

use Bluetea\PushNotifications\Model\MessageInterface;

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
        // TODO: Implement send() method.
    }
}
