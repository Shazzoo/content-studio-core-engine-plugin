<?php

namespace Shazzoo\ContentStudio\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Shazzoo\ContentStudio\Http\Controllers\SyncArticles;

class ContentStudioArticleSync extends Command
{
    protected $signature = 'content-studio:articles';

    protected $description = 'Sync content articles from the Content Studio API.';

    public function handle(SyncArticles $syncArticles): int
    {
        Log::info('[ContentStudioArticleSync] Starting article sync...');
        $this->info('Starting Content Studio sync...');

        $result = $syncArticles->__invoke(request());

        if (! is_array($result)) {
            $this->warn('Sync finished without structured output. Check logs.');

            return self::SUCCESS;
        }

        $this->line('Message: '.$result['message']);
        $this->line('Synced articles: '.($result['synced'] ?? 0));
        $this->line('Skipped (not approved): '.($result['skipped'] ?? 0));

        if (null !== ($result['expected'] ?? null)) {
            $this->line('Available on Engine: '.$result['expected']);
        }
        $this->line('Pages processed: '.($result['pages'] ?? 0));

        if (! empty($result['errors'])) {
            $first = $result['errors'][0] ?? [];
            $this->error('Sync failed. Status: '.($first['status'] ?? 'n/a'));
            if (! empty($first['url'])) {
                $this->line('URL: '.$first['url']);
            }

            return self::FAILURE;
        }

        $this->info('Content Studio sync completed successfully.');

        return self::SUCCESS;
    }
}
