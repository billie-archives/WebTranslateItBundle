<?php

namespace Ozean12\WebTranslateItBundle\DTO;

use Doctrine\Common\Collections\ArrayCollection;
use JMS\Serializer\Annotation as Serializer;

/**
 * Class ProjectDTO.
 */
class ProjectDTO
{
    /**
     * @var int
     *
     * @Serializer\Type("integer")
     */
    private $id;

    /**
     * @var string
     *
     * @Serializer\Type("string")
     */
    private $name;

    /**
     * @var ProjectFileDTO[]|ArrayCollection
     *
     * @Serializer\Type("ArrayCollection<Ozean12\WebTranslateItBundle\DTO\ProjectFileDTO>")
     */
    private $projectFiles;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     *
     * @return ProjectDTO
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return ProjectDTO
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return ArrayCollection|ProjectFileDTO[]
     */
    public function getProjectFiles()
    {
        return $this->projectFiles;
    }

    /**
     * @param ArrayCollection|ProjectFileDTO[] $projectFiles
     *
     * @return ProjectDTO
     */
    public function setProjectFiles($projectFiles)
    {
        $this->projectFiles = $projectFiles;

        return $this;
    }
}
