<?php

namespace App\Services\Providers;

use GuzzleHttp\Client;
use App\Contracts\NewsProviderInterface;

class NytProvider implements NewsProviderInterface
{
    protected $client;
    protected $apiKey;

    public function __construct()
    {
        $this->client = new Client(['base_uri' => 'https://api.nytimes.com/svc/']);
        $this->apiKey = env('NYT_KEY');
    }

    public function key(): string
    {
        return 'nyt';
    }

    public function name(): string
    {
        return 'New York Times';
    }

    public function fetch(int $page = 1, int $pageSize = 50): array
    {
        $res = $this->client->get('topstories/v2/home.json', [
            'query' => ['api-key' => $this->apiKey],
            'http_errors' => false
        ]);

        $data = json_decode($res->getBody()->getContents(), true);

        if (empty($data['results'])) return [];

        return array_map(function ($item) {
            return [
                'external_id' => md5($item['url']),
                'title' => $item['title'],
                'description' => $item['abstract'] ?? null,
                'content' => null,
                'url' => $item['url'],
                'urlToImage' => $item['multimedia'][0]['url'] ?? null,
                'publishedAt' => $item['published_date'] ?? null,
                'authorName' => $item['byline'] ?? null,
                'category' => $item['section'] ?? null,
                'language' => 'en',
                'raw' => $item,
            ];
        }, $data['results']);
    }
}
