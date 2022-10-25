<?php

namespace App\Jobs;

use App\Article;
use App\ArticleSection;
use FeedIo\Adapter\Guzzle\Client;
use FeedIo\Feed\ItemInterface;
use FeedIo\FeedIo;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Psr\Log\NullLogger;
use shweshi\OpenGraph\Facades\OpenGraphFacade as OpenGraph;

class FetchNewsArticles implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $sections;

    public function __construct(array $sections)
    {
        $this->sections = $sections;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $client = new Client(new \GuzzleHttp\Client([
            'connect_timeout' => 10,
            'read_timeout' => 30,
        ]));
        $logger = new NullLogger();
        $fio = new FeedIo($client, $logger);
        ArticleSection::whereIn('id', $this->sections)
            ->each(function (ArticleSection $section) use ($fio) {
                try {
                    $url = sprintf(
                        'https://news.google.com/rss/topics/%s?hl=%s',
                        $section->google_news_topic,
                        $section->google_news_language ?: 'en'
                    );
                    $items = $fio->read($url)->getFeed();
                } catch (\Exception $e) {
                }

                if (empty($items) || $items->count() <= 0) {
                    return;
                }

                /** @var ItemInterface $item */
                foreach ($items as $item) {
                    $guid = $item->getPublicId();
                    if (empty($guid) ) {
                        continue;
                    }

                    $checksum = hash('md5', $guid);
                    /** @var Article $existing */
                    $existing = Article::where('checksum', $checksum)->first();
                    if ($existing) {
                        try {
                            $existing->sections()->syncWithoutDetaching($section->id);
                        } catch (\Exception $e) {
                        }
                        continue;
                    }

                    try {
                        $og = OpenGraph::fetch($item->getLink());
                    } catch (\Exception $e) {
                    }

                    if (isset($og)) {
                        $title = $og['title'] ?? $item->getTitle();
                        if (strlen($title) > 255) {
                            $title = substr($title, 0, 255);
                        }

                        $section->articles()->create([
                            'title' => $title,
                            'snippet' => $og['description'] ?? null,
                            'image' => $og['image'] ?? null,
                            'link' => $og['url'] ?? $item->getLink(),
                            'source' => $item->getValue('source'),
                            'published_at' => $item->getLastModified(),
                            'checksum' => $checksum,
                        ]);
                    }
                }
                activity()
                    ->performedOn($section)
                    ->log($section->getDescriptionForEvent('refreshed'));
            });
    }
}
