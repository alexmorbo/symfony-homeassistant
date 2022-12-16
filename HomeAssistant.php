<?php

namespace AlexMorbo\HomeAssistant;

use AlexMorbo\HomeAssistant\Dto\Supervisor\Network\NetworkDto;
use Symfony\Component\HttpClient\Exception\TransportException;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class HomeAssistant
{
    public const SUPERVISOR_BASE_URL = 'http://supervisor';

    public const SUPERVISOR_NETWORK_INFO = '/network/info';

    private HttpClientInterface $client;

    public function __construct(
        private readonly ?string $supervisorToken = null,
        private readonly ?string $hassioToken = null,
        private SerializerInterface $serializer,
    ) {
        $this->client = HttpClient::createForBaseUri(self::SUPERVISOR_BASE_URL);
    }

    public function getSupervisorNetworkInfo(): ?NetworkDto
    {
        try {
            $response = $this->client->request(
                'GET',
                self::SUPERVISOR_BASE_URL . self::SUPERVISOR_NETWORK_INFO,
                [
                    'headers' => [
                        'Authorization' => sprintf('Bearer %s', $this->supervisorToken),
                    ],
                ]
            );

            $data = $response->toArray();
            if ($data['result'] !== 'ok') {
                throw new \RuntimeException('Can\'t get supervisor network info');
            }

            return $this->serializer->denormalize(
                $data['data'],
                NetworkDto::class
            );
        } catch (TransportException $e) {
            return null;
        }
    }
}