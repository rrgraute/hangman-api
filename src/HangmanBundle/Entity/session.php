<?php

namespace HangmanBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * session
 *
 * @ORM\Table(name="session")
 * @ORM\Entity(repositoryClass="HangmanBundle\Repository\sessionRepository")
 */
class session
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="unique_id", type="string", length=255, unique=true)
     */
    private $uniqueId;

    /**
     * @var int
     *
     * @ORM\Column(name="word", type="string", length=11)
     */
    private $word;

    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string", length=255)
     */
    private $status;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="ts", type="datetime")
     */
    private $ts;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set uniqueId
     *
     * @param string $uniqueId
     *
     * @return session
     */
    public function setUniqueId($uniqueId)
    {
        $this->uniqueId = $uniqueId;

        return $this;
    }

    /**
     * Get uniqueId
     *
     * @return string
     */
    public function getUniqueId()
    {
        return $this->uniqueId;
    }

    /**
     * Set wordId
     *
     * @param string $word
     *
     * @return session
     */
    public function setWord($word)
    {
        $this->word = $word;

        return $this;
    }

    /**
     * Get wordId
     *
     * @return string
     */
    public function getWord()
    {
        return $this->word;
    }

    /**
     * Set status
     *
     * @param string $status
     *
     * @return session
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set ts
     *
     * @param \DateTime $ts
     *
     * @return session
     */
    public function setTs($ts)
    {
        $this->ts = $ts;

        return $this;
    }

    /**
     * Get ts
     *
     * @return \DateTime
     */
    public function getTs()
    {
        return $this->ts;
    }
}
