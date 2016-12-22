<?php

namespace Ozean12\WebTranslateItBundle\Service;

use GuzzleHttp\Client;
use JMS\Serializer\SerializerInterface;
use Ozean12\WebTranslateItBundle\DTO\PullProjectResponseDTO;

/**
 * Class WebTranslateItRepository.
 */
class WebTranslateItRepository implements TranslationRepositoryInterface
{
    const FORMAT_JSON = 'json';
    const PULL_PROJECT_URL_PATTERN = '{base_url}projects/{token}.{format}';
    const PULL_FILE_URL_PATTERN = '{base_url}projects/{token}/files/...?file_path={name}';

    /**
     * @var string
     */
    private $readKey;

    /**
     * @var string
     */
    private $baseUrl;

    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * @var Client
     */
    private $client;

    /**
     * WebTranslateItRepository constructor.
     *
     * @param string              $readKey
     * @param string              $baseUrl
     * @param Client              $client
     * @param SerializerInterface $serializer
     */
    public function __construct(
        $readKey,
        $baseUrl,
        Client $client,
        SerializerInterface $serializer
    ) {
        $this->readKey = $readKey;
        $this->baseUrl = $baseUrl;
        $this->serializer = $serializer;
        $this->client = $client;
    }

    /**
     * {@inheritdoc}
     */
    public function pullProject()
    {
        $url = $this->getPullProjectUrl();

        $response = $this->client->get($url);
        $content = (string) $response->getBody();

        return $this->serializer->deserialize($content, PullProjectResponseDTO::class, self::FORMAT_JSON);
    }

    /**
     * {@inheritdoc}
     */
    public function pullFile($name)
    {
        $url = $this->getPullFileUrl($name);

        $response = $this->client->get($url);

        return (string) $response->getBody();
    }

    /**
     * @return string
     */
    private function getPullProjectUrl()
    {
        return str_replace(
            ['{base_url}', '{token}', '{format}'],
            [$this->baseUrl, $this->readKey, self::FORMAT_JSON],
            self::PULL_PROJECT_URL_PATTERN
        );
    }

    /**
     * @param string $name
     *
     * @return string
     */
    private function getPullFileUrl($name)
    {
        return str_replace(
            ['{base_url}', '{token}', '{name}'],
            [$this->baseUrl, $this->readKey, $name],
            self::PULL_FILE_URL_PATTERN
        );
    }
}
