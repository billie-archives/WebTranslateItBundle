<?php

namespace Ozean12\WebTranslateItBundle\Tests\Service;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use JMS\Serializer\SerializerInterface;
use Ozean12\WebTranslateItBundle\DTO\ProjectDTO;
use Ozean12\WebTranslateItBundle\DTO\PullProjectResponseDTO;
use Ozean12\WebTranslateItBundle\Service\WebTranslateItRepository;

/**
 * Class WebTranslateItRepositoryTest
 */
class WebTranslateItRepositoryTest extends \PHPUnit_Framework_TestCase
{
    const READ_KEY = 'test_key';
    const BASE_URL = 'test_base_url/';

    const FILE_URL = 'base_url/{token}/files/...?file_path={name}';
    const FILE_NAME = 'test_messages.en.yml';

    const PROJECT_URL_NORMALIZED = 'test_base_url/projects/test_key.json';
    const FILE_URL_NORMALIZED = 'test_base_url/projects/test_key/files/...?file_path=test_messages.en.yml';

    /**
     * @var WebTranslateItRepository
     */
    private $repository;

    /**
     * @var SerializerInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $serializer;

    /**
     * @var Client|\PHPUnit_Framework_MockObject_MockObject
     */
    private $client;

    /**
     * SetUp
     */
    public function setUp()
    {
        $this->serializer = $this->getMockBuilder(SerializerInterface::class)->getMock();
        $this->client = $this->getMockBuilder(Client::class)->setMethods(['get'])->getMock();

        $this->repository = new WebTranslateItRepository(
            self::READ_KEY,
            self::BASE_URL,
            $this->client,
            $this->serializer
        );

        parent::setUp();
    }

    /**
     * Test project pull
     */
    public function testPullProject()
    {
        $responseString = $this->getPullProjectResponseString();
        $response = new Response(200, [], $responseString);

        $this->client
            ->method('get')
            ->willReturn($response)
        ;

        $this->client
            ->expects($this->once())
            ->method('get')
            ->with(self::PROJECT_URL_NORMALIZED)
        ;

        $this->serializer
            ->method('deserialize')
            ->willReturn($this->getProjectDTO())
        ;

        $this->serializer
            ->expects($this->once())
            ->method('deserialize')
            ->with($responseString, PullProjectResponseDTO::class, WebTranslateItRepository::FORMAT_JSON)
        ;

        $this->assertEquals($this->getProjectDTO(), $this->repository->pullProject());
    }

    /**
     * Test pull file
     */
    public function testPullFile()
    {
        $responseString = $this->getPullFileResponseString();
        $response = new Response(200, [], $responseString);

        $this->client
            ->method('get')
            ->willReturn($response)
        ;

        $this->client
            ->expects($this->once())
            ->method('get')
            ->with(self::FILE_URL_NORMALIZED)
        ;

        $this->assertEquals($responseString, $this->repository->pullFile(self::FILE_NAME));
    }

    /**
     * @return string
     */
    private function getPullProjectResponseString()
    {
        return '{"project":{"id":"45"}}';
    }

    /**
     * @return ProjectDTO
     */
    private function getProjectDTO()
    {
        $projectDTO = new ProjectDTO();

        return $projectDTO
            ->setId(45)
            ->setName('test')
        ;
    }

    /**
     * @return string
     */
    private function getPullFileResponseString()
    {
        return 'hello: Hello';
    }
}
