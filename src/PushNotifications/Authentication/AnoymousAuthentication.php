<?php

namespace PushNotifications\Authentication;

class AnonymousAuthentication implements AuthenticationInterface
{
    /**
     * @return null
     */
    public function getCredential()
    {
        return null;
    }

    /**
     * @return null
     */
    public function getUsername()
    {
        return null;
    }

    /**
     * @return null
     */
    public function getPassword()
    {
        return null;
    }
}
