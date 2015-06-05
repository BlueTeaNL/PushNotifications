<?php

namespace Bluetea\PushNotifications\Model;

class Message implements MessageInterface
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
     * @var array
     */
    protected $_options;

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

    /**
     * Set custom options
     *
     * @param $options
     */
    public function setOptions($options)
    {
        $this->_options = $options;
    }

    /**
     * Get the message options.
     *
     * @return array
     */
    public function getOptions()
    {
        return $this->_options;
    }
}
