<?php

namespace Ozean12\WebTranslateItBundle\Service;

use Ozean12\WebTranslateItBundle\DTO\ProjectFileDTO;

/**
 * Interface FileServiceInterface.
 */
interface FileServiceInterface
{
    /**
     * Check if file content should be pulled.
     *
     * @param string         $realFilePath
     * @param ProjectFileDTO $projectFile
     *
     * @return bool
     */
    public function shouldBeUpdated($realFilePath, ProjectFileDTO $projectFile);

    /**
     * Put new file content to local file.
     *
     * @param string         $filePath
     * @param ProjectFileDTO $projectFile
     *
     * @return mixed
     */
    public function update($filePath, ProjectFileDTO $projectFile);

    /**
     * Prepare the translations directory.
     *
     * @param string $directory
     *
     * @return mixed
     */
    public function prepare($directory);
}
