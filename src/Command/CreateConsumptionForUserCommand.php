<?php

namespace App\Command;

use App\Entity\User;
use App\Manager\ConsumptionManager;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
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
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * CreateConsumptionsCommand constructor.
     * @param ConsumptionManager $consumptionManager
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(ConsumptionManager $consumptionManager, EntityManagerInterface $entityManager)
    {
        parent::__construct();

        $this->consumptionManager = $consumptionManager;
        $this->entityManager = $entityManager;
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
        $user = $this->entityManager->getRepository(User::class)->find($input->getArgument('userId'));

        if ($user === null) {
            if ($output->isVerbose()) {
                $output->writeln(sprintf('No user found for id %s', $input->getArgument('userId')));
            }

            return;
        }

        $this->consumptionManager->createSingleConsumption($user, $output);
    }
}