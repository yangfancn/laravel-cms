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

#[Signature('crawler:test')]
#[Description('Crawler Command Example')]
class Test extends Command
{
    public function handle()
    {
        $crawler = new Crawler(output: $this->output, removeUnlessAttributes: true);

        $listRules = RuleCollection::make()->add(Rule::make('link', 'a', 'href'));

        $dataRules = RuleCollection::make()
            ->add(Rule::make('title', 'h1'))
            ->add(Rule::make('content', '.post-content', 'html', ['h1', '-.info', '-.border-t-3']));

        $crawler->setBaseUri('https://www.php-blog.cn')
            ->setConcurrency(3)
            ->crawlerList(['/html', '/linux', '/php'], '.border.p-5 .border-b-1', $listRules)
            ->filterList(new FilterPost)
            ->setCompressHtml()
            // ->crawlerData($dataRules, new SavePost)
            ->crawlerData($dataRules, function ($data) {
                dd($data);
            });
    }
}
