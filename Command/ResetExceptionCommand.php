<?php declare(strict_types=1);

namespace GlobeGroup\NotifierBundle\Command;

use GlobeGroup\NotifierBundle\Service\NotificationSendService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Class ResetExceptionCommand
 *
 * @package GlobeGroup\NotifierBundle\Command
 */
class ResetExceptionCommand extends Command
{
    private $notificationSendService;

    public function __construct(NotificationSendService $notificationSendService)
    {
        parent::__construct();

        $this->notificationSendService = $notificationSendService;
    }

    protected function configure(): void
    {
        $this->setName('notifier:remove-lock')
            ->setDescription('Remove notification lock for particular error.')
            ->addArgument('identifier', InputArgument::REQUIRED, 'Identifier of notification to remove');
    }

    protected function execute(
        InputInterface $input,
        OutputInterface $output
    ): int {
        $io = new SymfonyStyle($input, $output);

        $result = $this->notificationSendService->uncheckExceptionSend($input->getArgument('identifier'));

        if ($result) {
            $io->success('Notification unlocked!');

            return 0;
        }

        $io->error('Error occurred!');

        return 1;
    }
}
