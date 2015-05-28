<?php

namespace Bluetea\PushNotifications\Model;

abstract class Message implements MessageInterface
{
    /**
     * @var string
     */
    protected $_title;

    /**
     * @var string
     */
    protected $_content;

    /**
     * Sets the message title
     *
     * @param $title
     * @return self
     */
    public function setTitle($title)
    {
        $this->_title = $title;
    }

    /**
     * Get the message title.
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->_title;
    }

    /**
     * Sets the message content
     *
     * @param $content
     * @return self
     */
    public function setContent($content)
    {
        $this->_content = $content;
    }

    /**
     * Get the message content.
     *
     * @return string
     */
    public function getContent()
    {
        return $this->_content;
    }
}
