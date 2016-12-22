<?php

namespace Ozean12\WebTranslateItBundle\DTO;

use JMS\Serializer\Annotation as Serializer;

/**
 * Class PullProjectResponseDTO.
 */
class PullProjectResponseDTO
{
    /**
     * @var ProjectDTO
     *
     * @Serializer\Type("Ozean12\WebTranslateItBundle\DTO\ProjectDTO")
     */
    private $project;

    /**
     * @return ProjectDTO
     */
    public function getProject()
    {
        return $this->project;
    }

    /**
     * @param ProjectDTO $project
     */
    public function setProject($project)
    {
        $this->project = $project;
    }
}
