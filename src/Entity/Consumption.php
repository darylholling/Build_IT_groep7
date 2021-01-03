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
    private $dateTime;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $responseStatusCode;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $ardiunoNotified = false;

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
     * @return bool
     */
    public function isArdiunoNotified(): bool
    {
        return $this->ardiunoNotified;
    }

    /**
     * @param bool $ardiunoNotified
     */
    public function setArdiunoNotified(bool $ardiunoNotified): void
    {
        $this->ardiunoNotified = $ardiunoNotified;
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

    /**
     * @return string|null
     */
    public function getResponseStatusCode(): ?string
    {
        return $this->responseStatusCode;
    }

    /**
     * @param string|null $responseStatusCode
     */
    public function setResponseStatusCode(?string $responseStatusCode): void
    {
        $this->responseStatusCode = $responseStatusCode;
    }
}