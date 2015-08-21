<?php

namespace AppBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * @MongoDB\Document(db="app", collection="emails")
 * @MongoDB\HasLifecycleCallbacks
 */
class Email
{
    const REPOSITORY = 'AppBundle:Email';
    const STATUS_SENT = 1;
    const STATUS_TEMPORARY_ERROR = 2;
    const STATUS_PERMANENT_ERROR = 3;
    /**
     * @MongoDB\Id
     */
    private $id;
    /**
     * @MongoDB\String
     */
    private $type;
    /**
     * @MongoDB\String
     */
    private $emailAddress;

    /**
     * @MongoDB\Date
     */
    private $create_date;

    /**
     * @MongoDB\Date
     */
    private $update_date;

    /**
     * @MongoDB\Int
     */
    private $retry_count;
    /**
     * @return mixed
     */
    public function getCreateDate()
    {
        return $this->create_date;
    }

    /**
     * @param mixed $create_date
     * @return Email
     */
    public function setCreateDate($create_date)
    {
        $this->create_date = $create_date;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getUpdateDate()
    {
        return $this->update_date;
    }

    /**
     * @param mixed $update_date
     * @return Email
     */
    public function setUpdateDate($update_date)
    {
        $this->update_date = $update_date;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getRetryCount()
    {
        return $this->retry_count;
    }

    /**
     * @param mixed $retry_count
     * @return Email
     */
    public function setRetryCount($retry_count = 0)
    {
        $this->retry_count = $retry_count;
        return $this;
    }

    /**
     * @MongoDB\String
     */
    private $from;
    /**
     * @MongoDB\String
     */
    private $subject;
    /**
     * @MongoDB\Bin
     */
    private $body;
    /**
     * @MongoDB\Int
     */
    private $status;
    /**
     * @MongoDB\Hash
     */
    private $arguments;
    public function getId()
    {
        return $this->id;
    }
    public function getType()
    {
        return $this->type;
    }
    public function getEmailAddress()
    {
        return $this->emailAddress;
    }public function getFrom()
{
    return $this->from;
}
    public function getSubject()
    {
        return $this->subject;
    }
    public function getBody()
    {
        return $this->body;
    }
    public function getArguments()
    {
        return $this->arguments;
    }
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }
    public function setEmailAddress($emailAddress)
    {
        $this->emailAddress = $emailAddress;
        return $this;
    }
    public function setFrom($from)
    {
        $this->from = $from;
        return $this;
    }
    public function setSubject($subject)
    {
        $this->subject = $subject;
        return $this;
    }
    public function setBody($body)
    {
        $this->body = $body;
        return $this;
    }
    public function getStatus()
    {
        return $this->status;
    }public function setStatus($status)
{
    $this->status = $status;
    return $this;
}
    public function setArguments($arguments)
    {
        $this->arguments = $arguments;
        return $this;
    }

    /**
     *
     * @MongoDB\PrePersist
     */
    public function setDates()
    {
        $date = new \DateTime('now');
        $this->setCreateDate($date);
        $this->setUpdateDate($date);
        $this->setRetryCount(0);
    }
    /**
     *
     * @MongoDB\PreUpdate
     */
    public function updateFields()
    {
        $this->setUpdateDate(new \DateTime('now'));
    }
}