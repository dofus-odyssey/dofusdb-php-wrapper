<?php

namespace DofusOdyssey\DofusdbPhpWrapper;

use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class DofusDb
{
    private HttpClientInterface $httpClient;

    private string $baseUrl = 'https://api.dofusdb.fr';

    public function __construct()
    {
        $this->httpClient = HttpClient::create();
    }

    public function executeQuery(string $endpoint, string $rowQueryString, int $id = null): string
    {
        $response = $this->httpClient->request('GET', $this->getUrlFormatted($endpoint, $rowQueryString, $id));

        return $response->getContent();
    }

    private function getUrlFormatted(string $endpoint, string $rowQueryString, int $id = null): string
    {
        if ($id) {
            return "{$this->baseUrl}/{$endpoint}/{$id}?{$rowQueryString}";
        }

        return "{$this->baseUrl}/{$endpoint}?{$rowQueryString}";
    }
}
