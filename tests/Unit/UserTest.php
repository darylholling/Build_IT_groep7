<?php

namespace App\Tests\Unit;

use App\Entity\User;
use Hautelook\AliceBundle\PhpUnit\RefreshDatabaseTrait;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Class UserTest
 */
class UserTest extends KernelTestCase
{
    use RefreshDatabaseTrait;

    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    public function setUp(): void
    {
        self::bootKernel();

        $container = self::$container;

        $this->passwordEncoder = $container->get(UserPasswordEncoderInterface::class);
    }

    public function testEncodePassword()
    {
        $plainPassword = 'password123';
        $user = new User();
        $user->setUsername('Testuser123');
        $user->setEmail('test@user.nl');
        $user->setPassword(
            $this->passwordEncoder->encodePassword(
                $user,
                $plainPassword
            )
        );

        $this->assertNotEquals($user->getPassword(), $plainPassword);
    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }
}