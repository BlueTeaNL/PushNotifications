<?php

namespace Bluetea\PushNotifications\Model;

class OneSignalApp implements OneSignalAppInterface
{
    /** @var String */
    protected $id;
    /** @var String */
    protected $name;
    /** @var String */
    protected $env;
    /** @var String */
    protected $p12File;
    /** @var String */
    protected $p12Password;
    /** @var String */
    protected $googleAuthKey;

    /**
     * Sets the app id
     *
     * @param $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * Get the app id
     *
     * @return string
     */
    public function getId()
    {
        if (is_null($this->id)) {
            return '';
        }
        return $this->id;
    }

    /**
     * Sets the app name
     *
     * @param $appName
     */
    public function setName($appName)
    {
        $this->name = $appName;
    }

    /**
     * Get the app name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Sets the app environment (Either "sandbox" or "production")
     *
     * @param $env
     */
    public function setEnv($env)
    {
        $env = strtolower($env);
        if (strcmp($env, 'sandbox') || strcmp($env, 'production')) {
            $this->env = $env;
        } else {
            $this->env = 'production';
        }
    }

    /**
     * Get the app environment.
     *
     * @return string
     */
    public function getEnv()
    {
        return $this->env;
    }

    /**
     * Set your apple push notification p12 certificate file, converted to a string and Base64 encoded.
     *
     * @param $p12File
     */
    public function setP12File($p12File)
    {
        $this->p12File = $p12File;
    }

    /**
     * Get the app p12 file.
     *
     * @return string
     */
    public function getP12File()
    {
        return $this->p12File;
    }

    /**
     * Set password for the p12 file
     *
     * @param $p12Password
     */
    public function setP12Password($p12Password)
    {
        $this->p12Password = $p12Password;
    }

    /**
     * Get password for the p12 file
     *
     * @return string
     */
    public function getP12Password()
    {
        return $this->p12Password;
    }

    /**
     * Set your Google Push messaging auth key
     *
     * @param $googleAuthKey
     */
    public function setGooglePushAuthKey($googleAuthKey)
    {
        $this->googleAuthKey = $googleAuthKey;
    }

    /**
     * Get your Google Push messaging auth key
     *
     * @return string
     */
    public function getGooglePushAutoKey()
    {
        return $this->googleAuthKey;
    }
}
