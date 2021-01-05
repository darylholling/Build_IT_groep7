<?php

namespace App\Command;

use App\Entity\Consumption;
use App\Manager\ConsumptionManager;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Padam87\CronBundle\Annotation\Job;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class CreateConsumptionCommand
 * @Job()
 */
class CreateConsumptionCommand extends Command
{
    protected static $defaultName = 'app:create-consumptions';

    /**
     * @var ConsumptionManager
     */
    private ConsumptionManager $consumptionManager;
    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $entityManager;

    /**
     * CreateConsumptionCommand constructor.
     * @param ConsumptionManager $consumptionManager
     */
    public function __construct(ConsumptionManager $consumptionManager, EntityManagerInterface $entityManager)
    {
        parent::__construct();

        $this->consumptionManager = $consumptionManager;
        $this->entityManager = $entityManager;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|void
     * @throws Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output): void
    {
        $consumption = $this->entityManager->getRepository(Consumption::class)->findAll()[0];
        $this->consumptionManager->sendArdiunoRequest($consumption);
//        $this->consumptionManager->createConsumptions();
    }
}