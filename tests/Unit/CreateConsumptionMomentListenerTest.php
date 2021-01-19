<?php


namespace App\Tests\Unit;


use App\Entity\Consumption;
use App\Entity\ConsumptionMoment;
use App\Entity\User;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Hautelook\AliceBundle\PhpUnit\RefreshDatabaseTrait;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class CreateConsumptionMomentListenerTest extends KernelTestCase
{
    use RefreshDatabaseTrait;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var User
     */
    private $user;

    public function setUp(): void
    {
        self::bootKernel();

        $container = self::$container;

        $this->entityManager = $container->get(EntityManagerInterface::class);
        $this->user = $this->createTestUser();
    }

    public function testConsumptionIsCreatingWhenCreatingConsumptionMomentLaterDuringDay()
    {
        $consumptionCount = count($this->entityManager->getRepository(Consumption::class)->findBy([
            'user' => $this->user
        ]));

        $this->assertEquals(0, $consumptionCount);

        $this->createConsumptionMomentByDateTime(new DateTime('+1 hour'));

        $consumptionCount = count($this->entityManager->getRepository(Consumption::class)->findBy([
            'user' => $this->user
        ]));

        $this->assertEquals(1, $consumptionCount);
    }

    public function testConsumptionIsNotCreatingWhenCreatingConsumptionMomentEalierDuringDay()
    {
        $consumptionCount = count($this->entityManager->getRepository(Consumption::class)->findBy([
            'user' => $this->user
        ]));

        $this->assertEquals(0, $consumptionCount);

        $this->createConsumptionMomentByDateTime(new DateTime('-1 hour'));

        $consumptionCount = count($this->entityManager->getRepository(Consumption::class)->findBy([
            'user' => $this->user
        ]));

        $this->assertEquals(0, $consumptionCount);
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

    private function createConsumptionMomentByDateTime(DateTime $dateTime)
    {
        $consumptionMoment = new ConsumptionMoment();
        $consumptionMoment->setUser($this->user);
        $consumptionMoment->setDateTime($dateTime);

        $this->entityManager->persist($consumptionMoment);
        $this->entityManager->flush();
    }
}