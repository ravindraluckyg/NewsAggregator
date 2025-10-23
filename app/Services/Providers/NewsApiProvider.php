<?php

namespace App\Services\Providers;

use GuzzleHttp\Client;
use App\Contracts\NewsProviderInterface;

class NewsApiProvider implements NewsProviderInterface
{
    protected $client;
    protected $apiKey;

    public function __construct()
    {
        $this->client = new Client(['base_uri' => 'https://newsapi.org/v2/']);
        $this->apiKey = env('NEWSAPI_KEY');
    }

    public function key(): string
    {
        return 'newsapi';
    }

    public function name(): string
    {
        return 'NewsAPI.org';
    }

    /**
     * Fetch latest articles from NewsAPI.
     *
     * @param int $page
     * @param int $pageSize
     * @return array
     */
    public function fetch(int $page = 1, int $pageSize = 50): array
    {
        $res = $this->client->get('top-headlines', [
            'query' => [
                'language' => 'en',
                'pageSize' => $pageSize,
                'page' => $page,
                'apiKey' => $this->apiKey,
            ],
            'http_errors' => false
        ]);

        $data = json_decode($res->getBody()->getContents(), true);

        if (empty($data['articles'])) {
            return [];
        }

        return array_map(function ($item) {
            return [
                'external_id' => md5($item['url']),
                'title' => $item['title'],
                'description' => $item['description'] ?? null,
                'content' => $item['content'] ?? null,
                'url' => $item['url'],
                'urlToImage' => $item['urlToImage'] ?? null,
                'publishedAt' => $item['publishedAt'] ?? null,
                'authorName' => $item['author'] ?? null,
                'category' => null,
                'language' => $item['language'] ?? 'en',
                'raw' => $item,
            ];
        }, $data['articles']);
    }
}
