<?php

namespace Ozean12\WebTranslateItBundle\Tests\Service;

use Ozean12\WebTranslateItBundle\DTO\ProjectFileDTO;
use Ozean12\WebTranslateItBundle\Service\WebTranslateItFileService;
use Ozean12\WebTranslateItBundle\Service\WebTranslateItRepository;

/**
 * Class WebTranslateItFileServiceTest
 */
class WebTranslateItFileServiceTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var WebTranslateItFileService
     */
    private $service;

    /**
     * @var WebTranslateItRepository|\PHPUnit_Framework_MockObject_MockObject
     */
    private $translationRepository;

    /**
     * Set up
     */
    public function setUp()
    {
        $this->translationRepository = $this
            ->getMockBuilder(WebTranslateItRepository::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;

        $this->service = new WebTranslateItFileService($this->translationRepository);

        parent::setUp();
    }

    /**
     * Tear down
     */
    public function tearDown()
    {
        $testDirectory = $this->getTranslationDirectory();
        if (file_exists($testDirectory)) {
            $testFile = sprintf('%s/%s', $testDirectory, $this->getProjectFileDTO()->getName());
            if (file_exists($testFile)) {
                unlink($testFile);
            }
            rmdir($testDirectory);
        }

        parent::tearDown();
    }

    /**
     * Test should be updated with local file not present
     */
    public function testShouldBeUpdatedWithNoLocalFile()
    {
        $this->service->prepare($this->getTranslationDirectory());

        $projectFile = new ProjectFileDTO();
        $filePath = sprintf('%s/%s', $this->getTranslationDirectory(), 'some_other_file.yml');

        $this->assertEquals(true, $this->service->shouldBeUpdated($filePath, $projectFile));
    }

    /**
     * Test should be updated
     *
     * @dataProvider shouldBeUpdatedDataProvider
     *
     * @param string $localTimestamp
     * @param string $remoteTimestamp
     * @param string $localContent
     * @param string $remoteContent
     * @param bool   $expectedResult
     * @param string $message
     */
    public function testShouldBeUpdated(
        string $localTimestamp,
        string $remoteTimestamp,
        string $localContent,
        string $remoteContent,
        bool $expectedResult,
        string $message
    ) {
        $this->service->prepare($this->getTranslationDirectory());
        $filePath = sprintf('%s/%s', $this->getTranslationDirectory(), $this->getProjectFileDTO()->getName());
        file_put_contents($filePath, $localContent);
        touch($filePath, (new \DateTime($localTimestamp))->getTimestamp());

        $projectFile = new ProjectFileDTO();
        $projectFile
            ->setUpdatedAt(new \DateTime($remoteTimestamp))
            ->setHashFile(sha1($remoteContent))
        ;

        $this->assertEquals($expectedResult, $this->service->shouldBeUpdated($filePath, $projectFile), $message);
    }

    /**
     * @return array
     */
    public function shouldBeUpdatedDataProvider(): array
    {
        return [
            ['today', 'today', 'hello', 'hello', false, 'Everything equal - skip'],
            ['yesterday', 'today', 'hello', 'hello', true, 'Local file is older - pull'],
            ['today', 'yesterday', 'hello', 'hello', false, 'Local file is newer - skip'],
            ['today', 'today', 'hello', 'hello1', true, 'Hash doesn\'t match - pull'],
        ];
    }

    /**
     * Test update
     */
    public function testUpdate()
    {
        $projectFile = $this->getProjectFileDTO();
        $filePath = sprintf('%s/%s', $this->getTranslationDirectory(), $projectFile->getName());
        $this->translationRepository->method('pullFile')->willReturn($this->getPullFileContent());
        $this->translationRepository->expects($this->once())->method('pullFile')->with($projectFile->getName());

        $this->service->prepare($this->getTranslationDirectory());
        $this->service->update($filePath, $projectFile);
        $this->assertFileExists($filePath);
    }

    /**
     * Test prepare
     */
    public function testPrepare()
    {
        $this->service->prepare($this->getTranslationDirectory());
        $this->assertFileExists($this->getTranslationDirectory());
    }

    /**
     * @return string
     */
    private function getTranslationDirectory(): string
    {
        return __DIR__.'/translations';
    }

    /**
     * @return string
     */
    private function getPullFileContent(): string
    {
        return 'hello: Hello';
    }

    /**
     * @return ProjectFileDTO
     */
    private function getProjectFileDTO(): ProjectFileDTO
    {
        $file = new ProjectFileDTO();

        return $file
            ->setId(43)
            ->setName('test_messages.yml')
        ;
    }
}
