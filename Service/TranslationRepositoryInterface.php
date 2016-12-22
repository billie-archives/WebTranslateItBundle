<?php

namespace Ozean12\WebTranslateItBundle\Service;

use Ozean12\WebTranslateItBundle\DTO\PullProjectResponseDTO;

/**
 * Interface TranslationRepositoryInterface.
 */
interface TranslationRepositoryInterface
{
    /**
     * @return PullProjectResponseDTO
     */
    public function pullProject();

    /**
     * @param string $name
     *
     * @return string
     */
    public function pullFile($name);
}
