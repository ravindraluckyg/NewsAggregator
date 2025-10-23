<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\NewsAggregatorService;
use App\Services\Providers\{NewsApiProvider, GuardianProvider, NytProvider};

class FetchNews extends Command
{
    protected $signature = 'news:fetch {provider?}';
    protected $description = 'Fetch latest news articles from providers';

    public function handle(NewsAggregatorService $service)
    {
        $providers = [
            'newsapi' => new NewsApiProvider(),
            'guardian' => new GuardianProvider(),
            'nyt' => new NytProvider(),
        ];

        $requested = $this->argument('provider');

        if ($requested && isset($providers[$requested])) {
            $service->importFrom($providers[$requested]);
        } else {
            foreach ($providers as $provider) {
                $service->importFrom($provider);
            }
        }

        $this->info('News import completed.');
    }
}
