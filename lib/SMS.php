<?php

namespace OKSWeb\SMS;

/**
 * Class SMS.
 */
class SMS
{
    private $receiver = '';
    private $message  = '';
    private $senderId = 0;
    private $unicode  = false;

    public function getReceiver()
    {
        return $this->receiver;
    }

    /**
     * @param string $receiver
     *
     * @return SMS
     */
    public function setReceiver($receiver)
    {
        $this->receiver = $receiver;

        return $this;
    }

    public function getSenderId()
    {
        return $this->senderId;
    }

    /**
     * @param integer $senderId
     *
     * @return SMS
     */
    public function setSenderId($senderId)
    {
        $this->senderId = $senderId;

        return $this;
    }

    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param string $message
     *
     * @return SMS
     */
    public function setMessage($message)
    {
        $this->message = $message;
        $this->unicode = $this->isUnicode();

        return $this;
    }

    /**
     * @return bool
     */
    public function getUnicode()
    {
        return $this->unicode;
    }

    /**
     *
     * @return bool
     */
    public function isUnicode()
    {
        if (\strlen($this->message) !== \strlen(\utf8_decode($this->message))) {
            return true;
        }

        return false;
    }
}
