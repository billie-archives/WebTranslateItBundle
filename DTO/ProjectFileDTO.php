<?php

namespace Ozean12\WebTranslateItBundle\DTO;

use JMS\Serializer\Annotation as Serializer;

/**
 * Class ProjectFileDTO.
 */
class ProjectFileDTO
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
     * @var string
     *
     * @Serializer\Type("string")
     */
    private $hashFile;

    /**
     * @var \DateTime
     *
     * @Serializer\Type("DateTime")
     */
    private $updatedAt;

    /**
     * @var int
     *
     * @Serializer\Type("string")
     */
    private $localeCode;

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
     * @return ProjectFileDTO
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
     * @return ProjectFileDTO
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getHashFile()
    {
        return $this->hashFile;
    }

    /**
     * @param string $hashFile
     *
     * @return ProjectFileDTO
     */
    public function setHashFile($hashFile)
    {
        $this->hashFile = $hashFile;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @param \DateTime $updatedAt
     *
     * @return ProjectFileDTO
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @return string
     */
    public function getLocaleCode()
    {
        return $this->localeCode;
    }

    /**
     * @param string $localeCode
     *
     * @return ProjectFileDTO
     */
    public function setLocaleCode($localeCode)
    {
        $this->localeCode = $localeCode;

        return $this;
    }
}
