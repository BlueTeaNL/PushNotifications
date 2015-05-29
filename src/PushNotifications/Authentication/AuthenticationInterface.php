<?php

namespace PushNotifications\Authentication;

interface AuthenticationInterface
{
    /**
     * @return string|null
     */
    public function getCredential();

    /**
     * @return string|null
     */
    public function getUsername();

    /**
     * @return string|null
     */
    public function getPassword();
}