<?php

namespace HangmanBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * guess
 *
 * @ORM\Table(name="guess")
 * @ORM\Entity(repositoryClass="HangmanBundle\Repository\guessRepository")
 */
class guess
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
     * @var int
     *
     * @ORM\Column(name="session_unique_id", type="integer")
     */
    private $sessionUniqueId;

    /**
     * @var string
     *
     * @ORM\Column(name="guess", type="string", length=1)
     */
    private $guess;


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
     * Set sessionUniqueId
     *
     * @param integer $sessionUniqueId
     *
     * @return guess
     */
    public function setSessionUniqueId($sessionUniqueId)
    {
        $this->sessionUniqueId = $sessionUniqueId;

        return $this;
    }

    /**
     * Get sessionUniqueId
     *
     * @return int
     */
    public function getSessionUniqueId()
    {
        return $this->sessionUniqueId;
    }

    /**
     * Set guess
     *
     * @param string $guess
     *
     * @return guess
     */
    public function setGuess($guess)
    {
        $this->guess = $guess;

        return $this;
    }

    /**
     * Get guess
     *
     * @return string
     */
    public function getGuess()
    {
        return $this->guess;
    }
}

