<?php

namespace App\Command;

use App\Manager\ConsumptionManager;
use Exception;
use Padam87\CronBundle\Annotation\Job;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class CreateConsumptionForUserCommand
 */
class CreateConsumptionForUserCommand extends Command
{
    protected static $defaultName = 'app:create-consumption';

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
     *
     */
    protected function configure()
    {
        $this->addArgument('userId', InputArgument::REQUIRED, 'User id');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|void
     * @throws Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output): void
    {
        $userId = $input->getArgument('userId');

        $this->consumptionManager->createSingleConsumption($userId, $output);
    }
}