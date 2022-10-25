<?php

namespace App\Console\Commands;

use App\ArticleSection;
use App\Jobs\FetchNewsArticles;
use Illuminate\Console\Command;

class CrawlLatestArticles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:crawl-articles';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetches latest news articles from Google News (if set).';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $sections = ArticleSection::whereNotNull('google_news_topic')->get();
        foreach ($sections as $section) {
            $this->info('Fetching news: ' . $section->name);
            dispatch_now(new FetchNewsArticles((array)$section->id));
        }
    }
}
