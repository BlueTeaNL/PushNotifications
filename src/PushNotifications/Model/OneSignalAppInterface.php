<?php

namespace Bluetea\PushNotifications\Model;

interface OneSignalAppInterface
{
    /**
     * Sets the app id
     *
     * @param $id
     */
    public function setId($id);

    /**
     * Get the app id
     *
     * @return string
     */
    public function getId();

    /**
     * Sets the app name
     *
     * @param $appName
     */
    public function setName($appName);

    /**
     * Get the app name
     *
     * @return string
     */
    public function getName();

    /**
     * Sets the app environment (Either "sandbox" or "production")
     *
     * @param $env
     */
    public function setEnv($env);

    /**
     * Get the app environment.
     *
     * @return string
     */
    public function getEnv();

    /**
     * Set your apple push notification p12 certificate file, converted to a string and Base64 encoded.
     *
     * @param $p12File
     */
    public function setP12File($p12File);

    /**
     * Get the app p12 file.
     *
     * @return string
     */
    public function getP12File();

    /**
     * Set password for the p12 file
     *
     * @param $p12Password
     */
    public function setP12Password($p12Password);

    /**
     * Get password for the p12 file
     *
     * @return string
     */
    public function getP12Password();

    /**
     * Set your Google Push messaging auth key
     *
     * @param $googleAuthKey
     */
    public function setGooglePushAuthKey($googleAuthKey);

    /**
     * Get your Google Push messaging auth key
     *
     * @return string
     */
    public function getGooglePushAutoKey();
}
