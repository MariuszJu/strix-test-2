<?php

namespace RockSolidSoftware\StrixTest\Util;

class FlashMessage
{

    const SUCCESS = 'success';
    const ERROR = 'danger';
    const DANGER = 'danger';
    const INFO = 'info';
    const WARN = 'warning';

    private $messageFormat = '<div class="alert alert-%s">%s</div>';

    /**
     * Add message
     *
     * @param string $type
     * @param string $message
     * @param bool   $checkIfAlreadyExists
     * @return bool
     */
    public function addMessage(string $type, string $message, bool $checkIfAlreadyExists = false)
    {
        if ($type == 'error') {
            $type = 'danger';
        }

        if (!in_array($type, [self::SUCCESS, self::ERROR, self::INFO, self::WARN])) {
            return false;
        }

        if ($checkIfAlreadyExists) {
            $messages = isset($_SESSION['messanger_messages'][$type]) ? $_SESSION['messanger_messages'][$type] : [];
            if (is_array($messages) && in_array($message, $messages)) {
                return false;
            }
        }

        $_SESSION['messanger_messages'][$type][] = $message;

        return true;
    }

    /**
     * @param string $message
     * @param bool   $checkIfAlreadyExists
     */
    public function addSuccessMessage(string $message, bool $checkIfAlreadyExists = true)
    {
        self::addMessage(self::SUCCESS, $message, $checkIfAlreadyExists);
    }

    /**
     * @param string $message
     * @param bool   $checkIfAlreadyExists
     */
    public function addErrorMessage(string $message, bool $checkIfAlreadyExists = true)
    {
        self::addMessage(self::ERROR, $message, $checkIfAlreadyExists);
    }

    /**
     * @param string $message
     * @param bool   $checkIfAlreadyExists
     */
    public function addInfoMessage(string $message, bool $checkIfAlreadyExists = true)
    {
        self::addMessage(self::INFO, $message, $checkIfAlreadyExists);
    }

    /**
     * @param string $message
     * @param bool   $checkIfAlreadyExists
     */
    public function addWarningMessage(string $message, bool $checkIfAlreadyExists = true)
    {
        self::addMessage(self::WARN, $message, $checkIfAlreadyExists);
    }

    /**
     * Get messages
     *
     * @param string $type
     * @param bool   $clear
     * @return array|false
     */
    public function getMessages(string $type = null, bool $clear = true)
    {
        if (empty($type)) {
            $messages = isset($_SESSION['messanger_messages']) ? $_SESSION['messanger_messages'] : [];

            if ($clear) {
                unset($_SESSION['messanger_messages']);
            }

            return is_array($messages) ? $messages : [];
        }

        if (!in_array($type, [self::SUCCESS, self::ERROR, self::INFO, self::WARN])) {
            return false;
        }

        $messages = isset($_SESSION['messanger_messages'][$type]) ? $_SESSION['messanger_messages'][$type] : [];

        if ($clear) {
            unset($_SESSION['messanger_messages'][$type]);
        }

        return is_array($messages) ? $messages : [];
    }

    /**
     * @param string $type
     * @param string $message
     * @return string
     */
    public function staticMessage(string $type = null, string $message)
    {
        return sprintf($this->messageFormat, $type == 'error' ? 'danger' : $type, $message);
    }
    
}
