<?php

namespace PushNotifications\Transport;

use PushNotifications\Model\MessageInterface;

class AppceleratorTransport extends AbstractTransport
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