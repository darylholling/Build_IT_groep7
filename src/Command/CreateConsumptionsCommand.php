<?php

namespace App\Command;

use App\Manager\ConsumptionManager;
use Exception;
use Padam87\CronBundle\Annotation\Job;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class CreateConsumptionsCommand
 * @Job()
 */
class CreateConsumptionsCommand extends Command
{
    protected static $defaultName = 'app:create-consumptions';

    /**
     * @var ConsumptionManager
     */
    private $consumptionManager;

    /**
     * CreateConsumptionsCommand constructor.
     * @param ConsumptionManager $consumptionManager
     */
    public function __construct(ConsumptionManager $consumptionManager)
    {
        parent::__construct();

        $this->consumptionManager = $consumptionManager;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|void
     * @throws Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output): void
    {
        $this->consumptionManager->createConsumptions();
    }
}