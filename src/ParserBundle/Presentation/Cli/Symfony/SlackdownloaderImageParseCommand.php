<?php

namespace App\ParserBundle\Presentation\Cli\Symfony;

use App\ParserBundle\Application\AuthenticateShoprenterWorker\AuthenticateShoprenterWorkerQuery;
use App\ParserBundle\Application\Exception\ApplicationException;
use App\ParserBundle\Application\GenerateImagesFromSlackExport\GenerateImagesFromSlackExportQuery;
use App\ParserBundle\Application\GetImagesFromFile\GetImagesFromFileQuery;
use App\ParserBundle\Application\UploadSlackExport\UploadSlackExportCommand;
use App\ParserBundle\Domain\MemeImage;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;

use function time;

class SlackdownloaderImageParseCommand extends Command
{
    use HandleTrait;

    protected static $defaultName = 'slackdownloader:image:parse';
    protected static $defaultDescription = 'It parsing urls form slack file';

    public function __construct(MessageBusInterface $queryBus) {
        parent::__construct();
        $this->messageBus = $queryBus;
    }

    protected function configure(): void
    {
        $this
            ->addArgument('username', InputArgument::REQUIRED, 'Username')
            ->addArgument('password', InputArgument::REQUIRED, 'Password')
            ->addArgument('filePath', InputArgument::REQUIRED, 'File path what is relative to command')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $filePath = $input->getArgument('filePath');

        try {
            $worker = $this->handle(new AuthenticateShoprenterWorkerQuery(
                $input->getArgument('username'),
                $input->getArgument('password')
            ));
        } catch (ApplicationException $e) {
            $io->error('Access Denied! ' . $e->getMessage());
            return Command::FAILURE;
        }

        $json = $this->handle(new UploadSlackExportCommand(
            $filePath,
            $filePath
        ));

        $urls = $this->handle(new GenerateImagesFromSlackExportQuery(
            $json,
            $worker->getId()
        ));

        /** @var MemeImage $url */
        foreach ($urls as $url) {
            $io->writeln($url->getUrl());
        }

        return Command::SUCCESS;
    }
}
