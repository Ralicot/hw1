<?php

namespace AppBundle\Communication\Email;

class Message
{
    /**
     * @return mixed
     */
    public function getTo()
    {
        return $this->to;
    }

    /**
     * @param mixed $to
     * @return Message
     */
    public function setTo($to)
    {
        $this->to = $to;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * @param mixed $subject
     * @return Message
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param mixed $message
     * @return Message
     */
    public function setMessage($message)
    {
        $this->message = $message;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getAdditionalHeaders()
    {
        return $this->additionalHeaders;
    }

    /**
     * @param mixed $additionalHeaders
     * @return Message
     */
    public function setAdditionalHeaders($additionalHeaders)
    {
        $this->additionalHeaders = $additionalHeaders;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getAdditionalParameters()
    {
        return $this->additionalParameters;
    }

    /**
     * @param mixed $additionalParameters
     * @return Message
     */
    public function setAdditionalParameters($additionalParameters)
    {
        $this->additionalParameters = $additionalParameters;
        return $this;
    }
    private $to;
    private $subject;
    private $message;
    private $additionalHeaders;
    private $additionalParameters;

}
