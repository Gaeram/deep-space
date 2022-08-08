<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class CallApiService
{
    private $client;

    public function __construct(HttpClientInterface $client){
        $this->client = $client;
    }

    public function getSpacePicture(): array{
        $response = $this->client->request(
            "GET",
            "https://api.nasa.gov/planetary/apod?api_key=ikyz7OQVyZ0Gse18OvAw7aUzUpsMUGgr6NnWhAj0"
        );
        return $response->toArray();
    }
}