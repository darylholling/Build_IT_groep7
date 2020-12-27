<?php

namespace App\Entity;

use App\Traits\IdTrait;
use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class ConsumptionMoment
 * @ORM\Entity()
 */
class ConsumptionMoment
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
}