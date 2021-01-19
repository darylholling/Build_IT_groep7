<?php

namespace App\Tests\Unit;

use App\Controller\ResetPasswordController;
use App\Entity\ResetPasswordRequest;
use App\Entity\User;
use App\Traits\IdTrait;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Hautelook\AliceBundle\PhpUnit\RefreshDatabaseTrait;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use SymfonyCasts\Bundle\ResetPassword\Exception\ResetPasswordExceptionInterface;
use SymfonyCasts\Bundle\ResetPassword\Exception\TooManyPasswordRequestsException;
use SymfonyCasts\Bundle\ResetPassword\ResetPasswordHelper;
use SymfonyCasts\Bundle\ResetPassword\ResetPasswordHelperInterface;
use TheSeer\Tokenizer\Token;

/**
 * Class ResetPasswordControllerTest
 */
class ResetPasswordTest extends KernelTestCase
{
    use RefreshDatabaseTrait;

    /**
     * @var ResetPasswordHelper
     */
    private $resetPasswordHelper;

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

        $this->resetPasswordHelper = $container->get(ResetPasswordHelperInterface::class);
        $this->entityManager = $container->get(EntityManagerInterface::class);
        $this->user = $this->createTestUser();
    }

    /**
     * @throws ResetPasswordExceptionInterface
     * @throws TooManyPasswordRequestsException
     */
    public function testGenerateResetTokenNotNull()
    {
        $resetToken = $this->resetPasswordHelper->generateResetToken($this->user);

        $this->assertNotNull($resetToken);
    }

    /**
     * @throws ResetPasswordExceptionInterface
     * @throws TooManyPasswordRequestsException
     */
    public function testGenerateResetTokenUser()
    {
        $resetToken = $this->resetPasswordHelper->generateResetToken($this->user);

        $this->assertNotNull($resetToken);

        $this->assertNotNull($this->getTokenUser($resetToken ->getToken()));
    }

    /**
     * @throws ResetPasswordExceptionInterface
     * @throws TooManyPasswordRequestsException
     */
    public function testGenerateResetTokenUserEquals()
    {
        $resetToken = $this->resetPasswordHelper->generateResetToken($this->user);

        $this->assertNotNull($resetToken);

        $this->assertEquals($this -> user, $this->getTokenUser($resetToken ->getToken()));
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

    private function getTokenUser(String $token): User
    {
        return $this->resetPasswordHelper->validateTokenAndFetchUser($token);
    }
}
