<?php

namespace App\Tests\Unit;

use App\Manager\ConsumptionManager;
use Doctrine\ORM\EntityManagerInterface;
use Hautelook\AliceBundle\PhpUnit\RefreshDatabaseTrait;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

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

    public function setUp(): void
    {
        self::bootKernel();

        $container = self::$container;

        $this->consumptionManager = $container->get(ConsumptionManager::class);
        $this->entityManager = $container->get(EntityManagerInterface::class);
    }

    public function testCreateConsumptions()
    {

    }

    public function testCreateSingleConsumption()
    {
//        $consumption = $this->consumptionManager->createSingleConsumption();
    }

    public function testSendArduinoRequest()
    {

    }
}