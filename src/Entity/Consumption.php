<?php

namespace App\Entity;

use App\Traits\IdTrait;
use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Consumption
 * @ORM\Entity(repositoryClass="App\Repository\ConsumptionRepository")
 */
class Consumption
{
    use IdTrait;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="consumptions")
     */
    private $user;

    /**
     * @var DateTime
     *
     * @ORM\Column(type="datetime", nullable=false)
     */
    private $dateTime;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $arduinoNotified = false;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $taken = false;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $contactsNotified = false;

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
    public function getDateTime(): DateTime
    {
        return $this->dateTime;
    }

    /**
     * @param DateTime $dateTime
     */
    public function setDateTime(DateTime $dateTime): void
    {
        $this->dateTime = $dateTime;
    }

    /**
     * @param bool $arduinoNotified
     */
    public function setArduinoNotified(bool $arduinoNotified): void
    {
        $this->arduinoNotified = $arduinoNotified;
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
     * @param bool $contactsNotified
     */
    public function setContactsNotified(bool $contactsNotified): void
    {
        $this->contactsNotified = $contactsNotified;
    }
}