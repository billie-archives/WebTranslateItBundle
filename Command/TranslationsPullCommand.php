<?php

namespace Ozean12\WebTranslateItBundle\Command;

use Ozean12\WebTranslateItBundle\DTO\ProjectDTO;
use Ozean12\WebTranslateItBundle\DTO\ProjectFileDTO;
use Ozean12\WebTranslateItBundle\Service\FileServiceInterface;
use Ozean12\WebTranslateItBundle\Service\TranslationRepositoryInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Stopwatch\Stopwatch;

/**
 * Class TranslationsPullCommand.
 */
class TranslationsPullCommand extends Command
{
    /**
     * @var TranslationRepositoryInterface
     */
    private $translationRepository;

    /**
     * @var FileServiceInterface
     */
    private $fileService;

    /**
     * @var string
     */
    private $translationDirectory;

    /**
     * @var Stopwatch
     */
    private $stopwatch;

    /**
     * TranslationsPullCommand constructor.
     *
     * @param TranslationRepositoryInterface $translationRepository
     * @param FileServiceInterface           $fileService
     * @param string                         $translationsDirectory
     */
    public function __construct(
        TranslationRepositoryInterface $translationRepository,
        FileServiceInterface $fileService,
        $translationsDirectory
    ) {
        $this->translationRepository = $translationRepository;
        $this->fileService = $fileService;
        $this->translationDirectory = $translationsDirectory;

        parent::__construct('ozean12:webtranslateit:pull');
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setDescription('Pull latest translations from translation repository')
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->prepare($output);

        $project = $this->translationRepository->pullProject()->getProject();
        $this->showWelcomeMessage($project, $output);

        $totalFilesCount = $project->getProjectFiles()->count();
        $currentFileCount = 0;

        foreach ($project->getProjectFiles() as $projectFile) {
            $this->processFile($projectFile, ++$currentFileCount, $totalFilesCount, $output);
        }

        $this->finish($output);
    }

    /**
     * @param ProjectDTO      $project
     * @param OutputInterface $output
     */
    private function showWelcomeMessage(ProjectDTO $project, OutputInterface $output)
    {
        $output->writeln(
            sprintf(
                'Pulling translations for <info>%s</info> into <info>%s</info>',
                $project->getName(),
                realpath($this->translationDirectory)
            ),
            OutputInterface::VERBOSITY_VERBOSE
        );
    }

    /**
     * @param OutputInterface $output
     */
    private function prepare(OutputInterface $output)
    {
        if ($output->isVerbose()) {
            $this->stopwatch = new Stopwatch();
            $this->stopwatch->start('execute');
        }

        $this->fileService->prepare($this->translationDirectory);
    }

    /**
     * @param OutputInterface $output
     */
    private function finish(OutputInterface $output)
    {
        if ($output->isVerbose()) {
            $event = $this->stopwatch->stop('execute');
            $output->writeln(sprintf('Translations updated in %ss', $event->getDuration() / 1000));
        }
    }

    /**
     * @param ProjectFileDTO  $projectFile
     * @param int             $currentFileCount
     * @param int             $totalFilesCount
     * @param OutputInterface $output
     */
    private function processFile(
        ProjectFileDTO $projectFile,
        int $currentFileCount,
        int $totalFilesCount,
        OutputInterface $output
    ) {
        $filePath = sprintf('%s/%s', $this->translationDirectory, $projectFile->getName());
        $shouldBeUpdated = $this->fileService->shouldBeUpdated($filePath, $projectFile);

        $output->writeln(
            sprintf(
                '[%s / %s]: <info>%s</info> -> <info>%s</info>',
                $currentFileCount,
                $totalFilesCount,
                $projectFile->getName(),
                $shouldBeUpdated ? 'pulling new version' : 'skipping'
            ),
            OutputInterface::VERBOSITY_VERBOSE
        );

        if ($shouldBeUpdated) {
            $this->fileService->update($filePath, $projectFile);
        }
    }
}
