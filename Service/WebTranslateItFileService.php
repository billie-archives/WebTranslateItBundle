<?php

namespace Ozean12\WebTranslateItBundle\Service;

use Ozean12\WebTranslateItBundle\DTO\ProjectFileDTO;

/**
 * Class WebTranslateItFileService.
 */
class WebTranslateItFileService implements FileServiceInterface
{
    /**
     * @var TranslationRepositoryInterface
     */
    private $translationRepository;

    /**
     * WebTranslateItFileService constructor.
     *
     * @param TranslationRepositoryInterface $translationRepository
     */
    public function __construct(TranslationRepositoryInterface $translationRepository)
    {
        $this->translationRepository = $translationRepository;
    }

    /**
     * Check if file content should be pulled.
     *
     * If local file does not exist
     * OR local file last update datetime is before the remote file update datetime
     * OR local file hash is different from the remote file hash
     *
     * @param string         $realFilePath
     * @param ProjectFileDTO $projectFile
     *
     * @return bool
     */
    public function shouldBeUpdated($realFilePath, ProjectFileDTO $projectFile)
    {
        return !file_exists($realFilePath)
            || filemtime($realFilePath) < $projectFile->getUpdatedAt()->getTimestamp()
            || $projectFile->getHashFile() !== sha1_file($realFilePath)
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function update($filePath, ProjectFileDTO $projectFile)
    {
        $newFileContent = $this->translationRepository->pullFile($projectFile->getName());
        file_put_contents($filePath, $newFileContent);
    }

    /**
     * {@inheritdoc}
     */
    public function prepare($directory)
    {
        if (!file_exists($directory)) {
            mkdir($directory);
        }
    }
}
