<?php

namespace App\Console\Commands\Crawlers;

use App\Services\Crawler\Collections\RuleCollection;
use App\Services\Crawler\Crawler;
use App\Services\Crawler\FilterHandlers\FilterPost;
use App\Services\Crawler\Rule;
use App\Services\Crawler\SaveHandlers\SavePost;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('crawler:yahoo-news')]
#[Description('Crawler Articles From Yahoo')]
class YahooNews extends Command
{
    public function handle()
    {
        $categories = [
            1 => 'https://finance.yahoo.com/rss/latest-news/',
        ];

        $crawler = new Crawler(output: $this->output, removeUnlessAttributes: true, requestOptions: [
            'proxy' => env('HTTP_PROXY'),
        ]);

        $listRules = RuleCollection::make()
            ->add(Rule::make('link', 'link', 'text'))
            ->add(Rule::make('thumb', 'media\:content', 'url'));

        $postRules = RuleCollection::make()
            ->add(Rule::make('title', 'h1.cover-title'))
            ->add(Rule::make('content', '[data-testid="article-body"]', 'html', ['-.readmore', '-figure', '-iframe', '-video', 'a']));

        foreach ($categories as $category => $url) {
            $crawler->crawlerList($url, 'xpath://item', $listRules)
                ->filterList(new FilterPost)
                ->crawlerData($postRules, new SavePost, appendData: [
                    'category_id' => $category,
                    'user_id' => 1,
                ]);
        }
    }
}
