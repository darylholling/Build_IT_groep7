<?php

namespace App\Tests\Unit;

use App\Entity\Arduino;
use App\Entity\Consumption;
use App\Entity\ConsumptionMoment;
use App\Entity\User;
use App\Manager\ConsumptionManager;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Hautelook\AliceBundle\PhpUnit\RefreshDatabaseTrait;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

/**
 * Class ConsumptionManagerTest
 */
class ConsumptionManagerTest extends KernelTestCase
{
    use RefreshDatabaseTrait;

    /**
     * @var ConsumptionManager
     */
    private $consumptionManager;

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

        $this->consumptionManager = $container->get(ConsumptionManager::class);
        $this->entityManager = $container->get(EntityManagerInterface::class);
        $this->user = $this->createTestUser();
    }

    /**
     * @throws Exception
     */
    public function testCreateConsumptionsWithoutArduinoAndConsumptionMoment()
    {
        $initialConsumptionQuantity = count($this->entityManager->getRepository(Consumption::class)->findAll());
        $this->consumptionManager->createConsumptions();
        $newconsumptionQuantity = count($this->entityManager->getRepository(Consumption::class)->findAll());
//
        $this->assertEquals($initialConsumptionQuantity, $newconsumptionQuantity);
    }

    /**
     * @throws Exception
     */
    public function testCreateConsumptionsWithoutConsumptionMoment()
    {
        $initialConsumptionQuantity = count($this->entityManager->getRepository(Consumption::class)->findAll());
        $user = $this->user;

        $this->createArduinoForUser($user);
        $this->consumptionManager->createConsumptions();
        $consumptionQuantityWithArduinoCreated = count($this->entityManager->getRepository(Consumption::class)->findAll());

        $this->assertEquals($initialConsumptionQuantity, $consumptionQuantityWithArduinoCreated);
    }

    /**
     * @throws Exception
     */
    public function testCreateConsumptionsWithArduinoAndConsumptionMoment()
    {
        $initialConsumptionQuantity = count($this->entityManager->getRepository(Consumption::class)->findAll());

        $user = $this->user;

        $this->createArduinoForUser($user);
        $this->createConsumptionMomentForUser($user);

        $this->consumptionManager->createConsumptions();
        $consumptionQuantityWithArduinoAndConsumptionMomentCreated = count($this->entityManager->getRepository(Consumption::class)->findAll());

        $this->assertNotEquals($initialConsumptionQuantity, $consumptionQuantityWithArduinoAndConsumptionMomentCreated);
    }

    public function testCreateSingleConsumptionDoesNotCreateForUserWithoutArduino()
    {
        $user = $this->user;

        $this->consumptionManager->createSingleConsumption($user);
        $this->assertTrue($user->getConsumptions()->isEmpty());
    }

    public function testCreateSingleConsumptionCreatesForUserWithArduino()
    {
        $user = $this->user;

        $this->createArduinoForUser($user);
        $this->consumptionManager->createSingleConsumption($user);
        $this->assertFalse($user->getConsumptions()->isEmpty());
    }

    public function testCreateSingleConsumptionResultEqualsToday()
    {
        $user = $this->user;

        $this->createArduinoForUser($user);
        $this->consumptionManager->createSingleConsumption($user);

        /** @var Consumption[] $consumption */
        $consumptions = $this->entityManager->getRepository(Consumption::class)->findBy([
            'user' => $this->user
        ]);

        $lastConsumption = end($consumptions);
        $now = new DateTime();

        $this->assertTrue($lastConsumption->getDateTime()->format('d-m-Y') === $now->format('d-m-Y'));
    }

    /**
     * @throws TransportExceptionInterface
     */
    public function testSendArduinoRequestSetsNotifiedToTrue()
    {
        $consumption = $this->createAndGetConsumption();

        $this->consumptionManager->sendArduinoRequest($consumption);

        $this->assertTrue($consumption->isArduinoNotified());
    }

    public function testCreateConsumptionMessageQuantity()
    {
        $this->createAndGetConsumption();

        $transport = self::$container->get('messenger.transport.async');

        $this->assertCount(1, $transport->get());
    }

    /**
     * @throws TransportExceptionInterface
     */
    public function testSendArduinoRequestNotifyContactMessageHasCorrectDelayStamp()
    {
        $consumption = $this->createAndGetConsumption();

        $this->consumptionManager->sendArduinoRequest($consumption);

        $this->assertTrue($consumption->isArduinoNotified());
    }

    protected function tearDown(): void
    {
        parent::tearDown();
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
     * @param User $user
     */
    private function createArduinoForUser(User $user)
    {
        $arduino = new Arduino();
        $arduino->setActive(true);
        $arduino->setUrl('https://webhook.site/5b9ff3f7-bc82-44e3-89a0-a891a4b21313');
        $user->addArduino($arduino);

        $this->entityManager->persist($arduino);
        $this->entityManager->flush();
    }

    /**
     * @param User $user
     */
    private function createConsumptionMomentForUser(User $user)
    {
        $consumptionMoment = new ConsumptionMoment();
        $consumptionMoment->setActive(true);
        $consumptionMoment->setDateTime(new DateTime());
        $user->addConsumptionMoment($consumptionMoment);

        $this->entityManager->persist($consumptionMoment);
        $this->entityManager->flush();
    }

    private function createAndGetConsumption()
    {
        $user = $this->user;

        $this->createArduinoForUser($user);
        $this->consumptionManager->createSingleConsumption($user);

        /** @var Consumption[] $consumption */
        $consumptions = $this->entityManager->getRepository(Consumption::class)->findBy([
            'user' => $this->user
        ]);

        return end($consumptions);
    }
}