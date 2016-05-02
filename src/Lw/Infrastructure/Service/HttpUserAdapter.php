<?php

namespace Lw\Infrastructure\Service;

use GuzzleHttp\Client;

class HttpUserAdapter implements UserAdapter
{
    /**
     * @var Client
     */
    private $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function toBadges($id)
    {
        $response = $this->client->get(sprintf('/users/%s', $id), [
            'allow_redirects' => true,
            'headers' => [
                'Accept' => 'application/hal+json',
            ],
        ]);

        $badges = [];

        if (200 === $response->getStatusCode()) {
            $badges = (new UserTranslator())->toBadgesFromRepresentation(json_decode($response->getBody(), true));
        }

        return $badges;
    }
}
