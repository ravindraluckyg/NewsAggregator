<?php

namespace App\Services\Providers;

use GuzzleHttp\Client;
use App\Contracts\NewsProviderInterface;

class GuardianProvider implements NewsProviderInterface
{
    protected $client;
    protected $apiKey;

    public function __construct()
    {
        $this->client = new Client(['base_uri' => 'https://content.guardianapis.com/']);
        $this->apiKey = env('GUARDIAN_KEY');
    }

    public function key(): string
    {
        return 'guardian';
    }

    public function name(): string
    {
        return 'The Guardian';
    }

    public function fetch(int $page = 1, int $pageSize = 50): array
    {
        $res = $this->client->get('search', [
            'query' => [
                'page' => $page,
                'page-size' => $pageSize,
                'api-key' => $this->apiKey,
                'show-fields' => 'headline,body,byline,thumbnail'
            ],
            'http_errors' => false
        ]);

        $data = json_decode($res->getBody()->getContents(), true);

        if (empty($data['response']['results'])) return [];

        return array_map(function ($item) {
            return [
                'external_id' => $item['id'],
                'title' => $item['webTitle'],
                'description' => $item['fields']['headline'] ?? null,
                'content' => $item['fields']['body'] ?? null,
                'url' => $item['webUrl'],
                'urlToImage' => $item['fields']['thumbnail'] ?? null,
                'publishedAt' => $item['webPublicationDate'] ?? null,
                'authorName' => $item['fields']['byline'] ?? null,
                'category' => $item['sectionName'] ?? null,
                'language' => 'en',
                'raw' => $item,
            ];
        }, $data['response']['results']);
    }
}
