<?php


namespace App\Tests\Unit;


use App\Entity\Consumption;
use App\Entity\User;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Hautelook\AliceBundle\PhpUnit\RefreshDatabaseTrait;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Class ConsumptionRepositoryTest
 */
class ConsumptionRepositoryTest extends KernelTestCase
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

    public function testFindConsumptionForTodayReturnsAllConsumptionsForToday()
    {
        $this->createConsumptionForDateTime(new DateTime());

        $consumptions = $this->entityManager->getRepository(Consumption::class)->findConsumptionsForToday($this->user);

        $this->assertEquals(1, count($consumptions));

        $this->createConsumptionForDateTime(new DateTime('+1 day'));

        $this->assertEquals(1, count($consumptions));
    }

    public function testFindConsumptionForTodayReturnsOnlyConsumptionsForPassedUser()
    {
        $secondTestUser = $this->createSecondTestUser();

        $this->createConsumptionForDateTime(new DateTime());
        $consumptions = $this->entityManager->getRepository(Consumption::class)->findConsumptionsForToday($this->user);

        $consumptionsForSecondUser = $this->entityManager->getRepository(Consumption::class)->findBy([
           'user' => $secondTestUser->getId()
        ]);

        $this->assertEmpty($consumptionsForSecondUser);
        $this->assertNotEmpty($consumptions);
    }

    /**
     * @param DateTime $dateTime
     * @return Consumption
     */
    private function createConsumptionForDateTime(DateTime $dateTime): Consumption
    {
        $consumption = new Consumption();
        $consumption->setUser($this->user);
        $consumption->setDateTime($dateTime);

        $this->entityManager->persist($consumption);
        $this->entityManager->flush();

        return $consumption;
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

    /**
     * @return User
     */
    private function createSecondTestUser(): User
    {
        $user = new User();
        $user->setUsername('SecondTestuser123');
        $user->setEmail('secondtest@user.nl');
        $user->setPassword('password');
        $user->setPlainPassword('password');

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $user;
    }
}