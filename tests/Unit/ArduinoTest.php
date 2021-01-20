<?php

namespace App\Tests\Unit;

use App\Entity\Arduino;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Hautelook\AliceBundle\PhpUnit\RefreshDatabaseTrait;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Class ArduinoTest
 */
class ArduinoTest extends KernelTestCase
{
    use RefreshDatabaseTrait;

    /**
     * @var User
     */
    private $user;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function setUp(): void
    {
        self::bootKernel();

        $container = self::$container;

        $this->entityManager = $container->get(EntityManagerInterface::class);
        $this->user = $this->createTestUser();
    }

    public function testArduinoIsInactiveOnCreation()
    {
        $arduino = new Arduino();
        $arduino->setUrl('testurl.nl');

        $this->entityManager->persist($arduino);
        $this->entityManager->flush();

        $this->assertFalse($arduino->isActive());
    }
    
    /**
     * @return User
     */
    private function createTestUser(): User
    {
        $user = new User();
        $user->setUsername('Testuser123');
        $user->setEmail('test@user.nl');
        $user->setPassword('password');
        $user->setPlainPassword('password');

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $user;
    }
}