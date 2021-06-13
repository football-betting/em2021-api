<?php declare(strict_types=1);

namespace App\Component\Tip\Infrastructure;

use App\DataTransferObject\TipEventDataProvider;
use App\Repository\TipsRepository;
use App\Service\Message\MessageServiceInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class TipCommand extends Command
{
    private const COMMAND = 'send:tips';
    private const DESCRIPTION = 'Send all tips to RabbitMq';
    /**
     * @var \App\Repository\TipsRepository
     */
    private TipsRepository $tipsRepository;
    /**
     * @var \App\Service\Message\MessageServiceInterface
     */
    private MessageServiceInterface $messageService;

    /**
     * TipCommand constructor.
     *
     * @param \App\Repository\TipsRepository $tipsRepository
     * @param \App\Service\Message\MessageServiceInterface $messageService
     */
    public function __construct(
        TipsRepository $tipsRepository,
        MessageServiceInterface $messageService
    )
    {
        parent::__construct();
        $this->tipsRepository = $tipsRepository;
        $this->messageService = $messageService;
    }

    protected function configure(): void
    {
        $this
            ->setName(self::COMMAND)
            ->setDescription(self::DESCRIPTION)
        ;

    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $tips = $this->tipsRepository->findAll();

        foreach ($tips as $tip) {
            $matchIdArray = explode(':', $tip->getMatchId());
            $matchDate = new \DateTime($matchIdArray[0] . ' ' . $matchIdArray[1]);

            $tipEventDataProvider = new TipEventDataProvider();
            $tipEventDataProvider->setUser($tip->getUsername());
            $tipEventDataProvider->setTipTeam1($tip->getTipTeam1());
            $tipEventDataProvider->setTipTeam2($tip->getTipTeam2());
            $tipEventDataProvider->setMatchId($tip->getMatchId());
            $tipEventDataProvider->setTipDatetime($matchDate->format('Y-m-d H:i'));
            $tipEventDataProvider->setMatchId($tip->getMatchId());

            $this->messageService->send($tipEventDataProvider);
        }

        return 1;
    }
}
