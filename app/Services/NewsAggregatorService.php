<?php

namespace App\Services;

use App\Contracts\NewsProviderInterface;
use App\Models\{Article, Source, Author, Category};
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class NewsAggregatorService
{
    /**
     * Import articles from a provider
     *
     * @param NewsProviderInterface $provider
     * @return void
     */
    public function importFrom(NewsProviderInterface $provider): void
    {
        $page = 1;
        $pageSize = env('NEWS_FETCH_PAGE_SIZE', 50);

        do {
            $articles = $provider->fetch($page, $pageSize);
            if (empty($articles)) break;

            foreach ($articles as $a) {
                // Create or get source
                $source = Source::firstOrCreate(
                    ['key' => $provider->key()],
                    ['name' => $provider->name()]
                );

                // Create or get author
                $author = !empty($a['authorName'])
                    ? Author::firstOrCreate(['name' => $a['authorName']])
                    : null;

                // Create or get category
                $category = !empty($a['category'])
                    ? Category::firstOrCreate(['name' => $a['category']])
                    : null;

                // Parse publishedAt to MySQL datetime
                $publishedAt = isset($a['publishedAt'])
                    ? Carbon::parse($a['publishedAt'])->format('Y-m-d H:i:s')
                    : null;

                // Insert or update article
                Article::updateOrCreate(
                    [
                        'source_id' => $source->id,
                        'external_id' => $a['external_id'] ?? null,
                    ],
                    [
                        'title' => $a['title'] ?? null,
                        'description' => $a['description'] ?? null,
                        'content' => $a['content'] ?? null,
                        'url' => $a['url'] ?? null,
                        'url_to_image' => $a['urlToImage'] ?? null,
                        'published_at' => $publishedAt,
                        'author_id' => optional($author)->id,
                        'category_id' => optional($category)->id,
                        'language' => $a['language'] ?? 'en',
                        'raw' => isset($a['raw']) ? json_encode($a['raw']) : null,
                    ]
                );
            }

            $page++;
        } while (count($articles) === $pageSize);

        Log::info("Imported from provider: {$provider->key()}");
    }
}
