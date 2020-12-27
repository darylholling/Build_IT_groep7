<?php

namespace App\Entity;

use App\Traits\IdTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @UniqueEntity(fields="email", message="Email already taken")
 * @UniqueEntity(fields="username", message="Username already taken")
 */
class User implements UserInterface
{
    use IdTrait;

    /**
     * @var Consumption[]|Collection
     *
     * @ORM\OneToMany(targetEntity="App\Entity\Consumption", mappedBy="user")
     */
    private $consumptions;

    /**
     * @var ConsumptionMoment[]|Collection
     *
     * @ORM\OneToMany(targetEntity="App\Entity\ConsumptionMoment", mappedBy="user")
     */
    private $consumptionMoments;

    /**
     * @var Contact[]|Collection
     *
     * @ORM\OneToMany(targetEntity="App\Entity\User", mappedBy="user")
     */
    private $contacts;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     * @Assert\NotBlank()
     * @Assert\Email()
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     * @Assert\NotBlank()
     */
    private $username;

    /**
     * @Assert\NotBlank()
     */
    private $plainPassword;

    /**
     * The below length depends on the "algorithm" you use for encoding
     * the password, but this works well with bcrypt.
     *
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="array")
     */
    private $roles;

    /**
     * User constructor.
     */
    public function __construct()
    {
        $this->roles = array('ROLE_USER');
        $this->contacts = new ArrayCollection();
        $this->consumptionMoments = new ArrayCollection();
        $this->consumptions = new ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param $username
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }

    /**
     * @return mixed
     */
    public function getPlainPassword()
    {
        return $this->plainPassword;
    }

    /**
     * @param $password
     */
    public function setPlainPassword($password)
    {
        $this->plainPassword = $password;
    }

    /**
     * @return string|null
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * @return null
     */
    public function getSalt()
    {
        // The bcrypt and argon2i algorithms don't require a separate salt.
        // You *may* need a real salt if you choose a different encoder.
        return null;
    }

    /**
     * @return array
     */
    public function getRoles(): array
    {
        return $this->roles;
    }

    /**
     *
     */
    public function eraseCredentials()
    {
    }

    /**
     * @return Contact[]|ArrayCollection|Collection
     */
    public function getContacts()
    {
        return $this->contacts;
    }

    /**
     * @param Contact $contact
     */
    public function addContact(Contact $contact): void
    {
        if ($this->contacts->contains($contact) === false) {
            $contact->setUser($this);
            $this->contacts->add($contact);
        }
    }

    /**
     * @param Contact $contact
     */
    public function removeContact(Contact $contact): void
    {
        if ($this->contacts->contains($contact)) {
            $this->contacts->removeElement($contact);
        }
    }

    /**
     * @return ConsumptionMoment[]|ArrayCollection|Collection
     */
    public function getConsumptionMoments()
    {
        return $this->consumptionMoments;
    }

    /**
     * @param ConsumptionMoment $consumptionMoment
     */
    public function addConsumptionMoment(ConsumptionMoment $consumptionMoment): void
    {
        if ($this->consumptionMoments->contains($consumptionMoment) === false) {
            $consumptionMoment->setUser($this);
            $this->consumptionMoments->add($consumptionMoment);
        }
    }

    /**
     * @param ConsumptionMoment $consumptionMoment
     */
    public function removeConsumptionMoment(ConsumptionMoment $consumptionMoment): void
    {
        if ($this->consumptionMoments->contains($consumptionMoment)) {
            $this->consumptionMoments->removeElement($consumptionMoment);
        }
    }

    /**
     * @return Consumption[]|ArrayCollection|Collection
     */
    public function getConsumptions()
    {
        return $this->consumptions;
    }

    /**
     * @param Consumption $consumption
     */
    public function addConsumption(Consumption $consumption): void
    {
        if ($this->consumptions->contains($consumption) === false) {
            $consumption->setUser($this);
            $this->consumptions->add($consumption);
        }
    }

    /**
     * @param Consumption $consumption
     */
    public function removeConsumption(Consumption $consumption): void
    {
        if ($this->consumptions->contains($consumption)) {
            $this->consumptions->removeElement($consumption);
        }
    }
}
