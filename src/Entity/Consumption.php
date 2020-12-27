<?php

namespace App\Entity;

use App\Traits\IdTrait;
use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Consumption
 * @ORM\Entity()
 */
class Consumption
{
    use IdTrait;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="contacts")
     */
    private $user;

    /**
     * @var DateTime
     *
     * @ORM\Column(type="datetime", nullable=false)
     */
    private $time;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $taken;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $contactsNotified;

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @param User $user
     */
    public function setUser(User $user): void
    {
        $this->user = $user;
    }

    /**
     * @return DateTime
     */
    public function getTime(): DateTime
    {
        return $this->time;
    }

    /**
     * @param DateTime $time
     */
    public function setTime(DateTime $time): void
    {
        $this->time = $time;
    }

    /**
     * @return bool
     */
    public function isTaken(): bool
    {
        return $this->taken;
    }

    /**
     * @param bool $taken
     */
    public function setTaken(bool $taken): void
    {
        $this->taken = $taken;
    }

    /**
     * @return bool
     */
    public function isContactsNotified(): bool
    {
        return $this->contactsNotified;
    }

    /**
     * @param bool $contactsNotified
     */
    public function setContactsNotified(bool $contactsNotified): void
    {
        $this->contactsNotified = $contactsNotified;
    }
}