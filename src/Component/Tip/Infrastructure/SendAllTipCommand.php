<?php declare(strict_types=1);

namespace App\Component\Tip\Infrastructure;

use App\DataTransferObject\TipEventDataProvider;
use App\Repository\TipsRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class SendAllTipCommand extends Command
{
    /**
     * @var \Symfony\Component\Messenger\MessageBusInterface
     */
    private MessageBusInterface $messageBus;

    /**
     * @var \App\Repository\TipsRepository
     */
    private TipsRepository $tipsRepository;

    /**
     * @param \Symfony\Component\Messenger\MessageBusInterface $messageBus
     * @param \App\Repository\TipsRepository $tipsRepository
     */
    public function __construct(
        MessageBusInterface $messageBus,
        TipsRepository $tipsRepository
    )
    {
        parent::__construct();
        $this->messageBus = $messageBus;
        $this->tipsRepository = $tipsRepository;
    }

    protected static $defaultName = 'tip:send-all';

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $tipsEntityList = $this->tipsRepository->findAll();
        foreach ($tipsEntityList as $tipsEntity) {
            $tipEventDataProvider = new TipEventDataProvider();
            $tipEventDataProvider->setMatchId($tipsEntity->getMatchId());
            $tipEventDataProvider->setTipTeam1($tipsEntity->getTipTeam1());
            $tipEventDataProvider->setTipTeam2($tipsEntity->getTipTeam2());
            $tipEventDataProvider->setTipDatetime($tipsEntity->getMatchId());
            $tipEventDataProvider->setUser($tipsEntity->getUsername());

            $this->messageBus->dispatch($tipEventDataProvider);
        }

        return Command::SUCCESS;
    }
}
