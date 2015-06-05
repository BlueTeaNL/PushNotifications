<?php

namespace Bluetea\PushNotifications\Model;

interface MessageInterface
{
    /**
     * Sets the message title
     *
     * @param $title
     * @return self
     */
    public function setTitle($title);

    /**
     * Get the message title.
     *
     * @return string
     */
    public function getTitle();

    /**
     * Sets the message content
     *
     * @param $content
     * @return self
     */
    public function setContent($content);

    /**
     * Get the message content.
     *
     * @return string
     */
    public function getContent();

    /**
     * Set custom options
     */
    public function setOptions($options);

    /**
     * @return array
     */
    public function getOptions();
}
