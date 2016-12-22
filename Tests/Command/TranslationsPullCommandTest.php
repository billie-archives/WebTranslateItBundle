<?php

namespace Ozean12\WebTranslateItBundle\Tests\Command;

use Doctrine\Common\Collections\ArrayCollection;
use Ozean12\WebTranslateItBundle\Command\TranslationsPullCommand;
use Ozean12\WebTranslateItBundle\DTO\ProjectDTO;
use Ozean12\WebTranslateItBundle\DTO\ProjectFileDTO;
use Ozean12\WebTranslateItBundle\DTO\PullProjectResponseDTO;
use Ozean12\WebTranslateItBundle\Service\TranslationRepositoryInterface;
use Ozean12\WebTranslateItBundle\Service\WebTranslateItFileService;
use Ozean12\WebTranslateItBundle\Service\WebTranslateItRepository;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Tester\CommandTester;

/**
 * Class TranslationsPullCommandTest
 */
class TranslationsPullCommandTest extends \PHPUnit_Framework_TestCase
{
    private $projectName = "Yuri Gagarin";
    private $projectFileName = "Ole Einar Bjoerndalen";
    private $translationDirectory = "in/a/galaxy/far/far/away";

    /**
     * @var WebTranslateItRepository|\PHPUnit_Framework_MockObject_MockObject
     */
    private $repositoryMock;

    /**
     * @var WebTranslateItFileService|\PHPUnit_Framework_MockObject_MockObject
     */
    private $fileServiceMock;

    /**
     * @var Application
     */
    private $application;

    /**
     * @var Command
     */
    private $command;

    /**
     * @var CommandTester
     */
    private $commandTester;

    /**
     * Set up
     */
    public function setUp()
    {
        // Mocking of the repository
        $this->repositoryMock = $this->getMockBuilder(TranslationRepositoryInterface::class)
            ->disableOriginalConstructor()
            ->setMethods(['pullProject', 'pullFile'])
            ->getMock();

        $projectResponseDTO = new PullProjectResponseDTO();
        $projectDTO = (new ProjectDTO())
            ->setName($this->projectName)
            ->setProjectFiles(
                new ArrayCollection(
                    [
                        (new ProjectFileDTO())->setName($this->projectFileName),
                    ]
                )
            );

        $projectResponseDTO->setProject(
            $projectDTO
        );

        $this->repositoryMock->expects($this->any())->method('pullProject')->willReturn(
            $projectResponseDTO
        );

        $this->repositoryMock->expects($this->any())->method('pullFile')->willReturn($this->projectFileName);

        // Mocking of the file service

        $this->fileServiceMock = $this->getMockBuilder(WebTranslateItFileService::class)
            ->setConstructorArgs([$this->repositoryMock])
            ->setMethods(['prepare', 'update', 'shouldBeUpdated'])
            ->getMock();

        $this->fileServiceMock->expects($this->any())->method('shouldBeUpdated')->willReturn(true);

        $command = new TranslationsPullCommand(
            $this->repositoryMock,
            $this->fileServiceMock,
            $this->translationDirectory
        );

        $this->application = new Application();
        $this->application->add($command);

        $this->command = $this->application->find('ozean12:webtranslateit:pull');
        $this->commandTester = new CommandTester($this->command);
    }

    /**
     * Proves that the execution of the command is valid
     */
    public function testExecuteValid()
    {
        $this->commandTester->execute(
            [
                'command' => $this->command->getName(),
            ],
            [
                'verbosity' => OutputInterface::VERBOSITY_VERBOSE,
            ]
        );

        $this->assertRegExp('/Pulling translations for/', $this->commandTester->getDisplay());
        $this->assertRegExp('/Translations updated/', $this->commandTester->getDisplay());
    }
}
