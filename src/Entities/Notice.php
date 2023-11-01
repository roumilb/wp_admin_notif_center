<?php

namespace WANC\Entities;

class Notice
{
    /**
     * @var
     */
    private $id;
    /**
     * @var
     */
    private $content;
    /**
     * @var
     */
    private $userId;
    /**
     * @var
     */
    private $lastSeenDate;
    /**
     * @var
     */
    private $numberSeen;
    /**
     * @var
     */
    private $firstSeenDate;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id) {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param mixed $content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }

    /**
     * @return mixed
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * @param mixed $userId
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;
    }

    /**
     * @return mixed
     */
    public function getLastSeenDate()
    {
        return $this->lastSeenDate;
    }

    /**
     * @param mixed $lastSeenDate
     */
    public function setLastSeenDate($lastSeenDate)
    {
        $this->lastSeenDate = $lastSeenDate;
    }

    /**
     * @return mixed
     */
    public function getNumberSeen()
    {
        return $this->numberSeen;
    }

    /**
     * @param mixed $numberSeen
     */
    public function setNumberSeen($numberSeen)
    {
        $this->numberSeen = $numberSeen;
    }

    /**
     * @return mixed
     */
    public function getFirstSeenDate()
    {
        return $this->firstSeenDate;
    }

    /**
     * @param mixed $firstSeenDate
     */
    public function setFirstSeenDate($firstSeenDate)
    {
        $this->firstSeenDate = $firstSeenDate;
    }
}