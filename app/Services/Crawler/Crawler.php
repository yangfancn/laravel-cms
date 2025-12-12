<?php

namespace App\Services\Crawler;

use App\Services\Crawler\Collections\RequestCollection;
use App\Services\Crawler\Collections\RuleCollection;
use App\Services\Crawler\FilterHandlers\FilterAbstract;
use App\Services\Crawler\SaveHandlers\SaveAbstract;
use Closure;
use DOMDocument;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Pool;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use Symfony\Component\Console\Style\OutputStyle;
use Symfony\Component\DomCrawler\Crawler as DomCrawler;

class Crawler
{
    /**
     * The options for the GuzzleHTTP request.
     */
    protected array $requestOptions = [
        'verify' => false,
        'timeout' => 20,
        'headers' => [
            'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/89.0.4389.114 Safari/537.36',
        ],
    ];

    /**
     * The base URI for the crawler.
     */
    private string $baseUri;

    /**
     * The concurrency for the crawler.
     */
    protected int $concurrency = 1;

    /**
     * The interval between requests in seconds.
     */
    protected ?int $interval = null;

    /**
     * determine return html is compressed or not.
     */
    protected bool $compressHtml = false;

    /**
     * The list of crawled items.
     */
    public array $list = [];

    /**
     * laravel console output.
     */
    protected ?OutputStyle $output = null;

    /**
     * The attributes to remove from HTML elements unless specified.
     */
    protected array|false $removeUnlessAttributes = [
        'script' => ['src', 'type', 'async', 'defer'],
        'style' => ['type'],
        'link' => ['rel', 'href', 'type'],
        'img' => ['src', 'alt'],
        'a' => ['href', 'title'],
        'iframe' => ['src', 'title'],
    ];

    public function __construct(
        ?array $requestOptions = null,
        ?OutputStyle $output = null,
        ?int $interval = null,
        bool|array $removeUnlessAttributes = false
    ) {
        if ($requestOptions) {
            $this->requestOptions = array_merge($this->requestOptions, $requestOptions);

            if (isset($requestOptions['base_uri'])) {
                $this->baseUri = $requestOptions['base_uri'];
            }
        }

        $this->output = $output;

        if ($interval) {
            $this->interval = $interval;
        }

        $this->removeUnlessAttributes = $removeUnlessAttributes === false
            ? false
            : (
                is_array($removeUnlessAttributes)
                ? array_merge($this->removeUnlessAttributes, $removeUnlessAttributes)
                : $this->removeUnlessAttributes
            );
    }

    public function __get(string $name): mixed
    {
        if (property_exists($this, $name)) {
            return $this->$name;
        }

        throw new \InvalidArgumentException("Property {$name} does not exist on ".static::class);
    }

    /**
     * Set the base URI for the crawler.
     */
    public function setBaseUri(string $baseUri): self
    {
        $this->baseUri = $baseUri;
        $this->requestOptions['base_uri'] = $baseUri;

        return $this;
    }

    /**
     * Set crawler list
     *
     * @param  array  $list  [[link => string, [thumb] => string, ...], ...] link is required
     */
    public function setList(array $list): self
    {
        $this->list = $list;

        return $this;
    }

    /**
     * Set concurrency for the crawler.
     */
    public function setConcurrency(int $concurrency): self
    {
        $this->concurrency = $concurrency;

        return $this;
    }

    /**
     * Determine the crawlered html value is compressed or not.
     */
    public function setCompressHtml(bool $compressHtml = true): self
    {
        $this->compressHtml = $compressHtml;

        return $this;
    }

    /**
     * Send reqests
     */
    protected function request(RequestCollection $requests, Closure $success, ?Closure $failed = null): void
    {
        $client = new Client($this->requestOptions);

        $requests = function () use ($requests) {
            foreach ($requests->all() as $request) {
                yield $request;
            }
        };

        $pool = new Pool($client, $requests(), [
            'concurrency' => $this->concurrency,
            'fulfilled' => function (Response $response, $index) use ($success) {
                $success($response->getBody()->getContents(), $index);
                $this->interval && sleep($this->interval);
            },
            'rejected' => function (GuzzleException $exception, $index) use ($failed) {
                $failed && $failed($exception, $index);
                $this->output?->error($exception->getMessage());
                $this->interval && sleep($this->interval);
            },
        ]);

        $pool->promise()->wait();
    }

    /**
     * Query the HTML content using the provided rules.
     *
     * @param  string  $html  The HTML content to query.
     * @param  RuleCollection  $rules  The rules to apply for querying the HTML.
     * @param  string|null  $rangeSelector  Optional CSS selector to limit the scope of the query.
     * @return array The queried data as an array.
     */
    public function query(string $html, RuleCollection $rules, ?string $rangeSelector = null): array
    {
        $domCrawler = new DomCrawler($html);

        if ($rangeSelector) {
            return $domCrawler->filter($rangeSelector)->each(fn (DomCrawler $node) => $this->queryData($node, $rules));
        } else {
            return $this->queryData($domCrawler, $rules);
        }
    }

    /**
     * Query data from the DOM crawler based on the provided rules.
     */
    private function queryData(DomCrawler $domCrawler, RuleCollection $rules): array
    {
        $data = [];

        foreach ($rules as $rule) {
            $element = $rule->selector ? $domCrawler->filter($rule->selector) : $domCrawler;

            if (! $element->count()) {
                $this->output?->warning("Rule '{$rule->name}' did not match any elements.");
                $data[$rule->name] = null;

                continue;
            }

            if ($rule->attribute === 'texts') {
                $data[$rule->name] = $element->each(function (DomCrawler $item) use ($rule) {
                    $node = $this->cloneDomCrawler($item);
                    $rule->filterSelector && $this->removeChild($node, $rule->filterSelector);

                    return $node->text();
                });

                continue;
            }

            $node = $this->cloneDomCrawler($element);

            $rule->filterSelector && $this->removeChild($node, $rule->filterSelector);

            $data[$rule->name] = match ($rule->attribute) {
                'text' => $node->text(),
                'html' => (function (DomCrawler $ele) {
                    $this->removeUnlessAttributes && $this->removeAttrsUnless($ele);

                    return html_entity_decode($this->compressHtml ? self::compressHtml($ele->html()) : $ele->html());
                })($node),
                default => $node->attr($rule->attribute),
            };

            if ($rule->callback instanceof Closure) {
                $data[$rule->name] = ($rule->callback)($data[$rule->name]);
            }
        }

        return $data;
    }

    private function cloneDomCrawler(DomCrawler $domCrawler): DomCrawler
    {
        $cloneNode = $domCrawler->getNode(0)->cloneNode(true);
        $doc = new DOMDocument;
        $importedNode = $doc->importNode($cloneNode, true);
        $doc->appendChild($importedNode);

        return new DomCrawler($doc);
    }

    /**
     * Remove child elements from the DOM crawler based on the provided selectors.
     *
     * @param  string[]  $selectors
     * @return void
     */
    private function removeChild(DomCrawler $domCrawler, array $selectors)
    {
        foreach ($selectors as $selector) {
            if (str_starts_with($selector, '-')) {
                $domCrawler->filter(ltrim($selector, '-'))->each(function (DomCrawler $item) {
                    $node = $item->getNode(0);
                    $node->parentNode->removeChild($node);
                });
            } else {
                $this->unwrap($domCrawler, $selector);
            }
        }
    }

    /**
     * Remove element tag but keep its children.
     */
    private function unwrap(DomCrawler $domCrawler, string $selector): void
    {
        $domCrawler->filter($selector)->each(function (DomCrawler $item) use ($domCrawler) {
            $node = $item->getNode(0);

            if ($node === $domCrawler->getNode(0)) {
                return;
            }

            foreach (iterator_to_array($node->childNodes) as $child) {
                $node->parentNode->insertBefore($child, $node);
            }

            $node->parentNode->removeChild($node);
        });
    }

    /**
     * Remove attributes from elements unless they are in the specified list.
     */
    private function removeAttrsUnless(DomCrawler $domCrawler): void
    {
        $domCrawler->filter('*')->each(function ($item) {
            $node = $item->getNode(0);

            if ($node instanceof \DOMElement) {
                $removeAttributes = array_column(iterator_to_array($node->attributes), 'localName');

                if (isset($this->removeUnlessAttributes[$node->tagName])) {
                    $removeAttributes = array_diff($removeAttributes, $this->removeUnlessAttributes[$node->tagName]);
                }

                foreach ($removeAttributes as $attr) {
                    $node->removeAttribute($attr);
                }
            }
        });
    }

    /**
     * Compress HTML content by removing unnecessary whitespace and comments.
     *
     * @param  string  $html  The HTML content to compress.
     * @return string The compressed HTML content.
     */
    public static function compressHtml(string $html): string
    {
        return preg_replace([
            '/\>[^\S\r\n]+/',        // 去掉标签后的空白
            '/[^\S\r\n]+\</',        // 去掉标签前的空白
            '/\s+/',                 // 多余空白合并
            '/<!--.*?-->/s',          // 去掉注释（非贪婪模式 + 支持多行）
        ], [
            '>',
            '<',
            ' ',
            '',
        ], $html);
    }

    /**
     * Crawler list
     *
     * @param  string|string[]  $urls
     */
    public function crawlerList(string|array $urls, string $rangeSelector, RuleCollection $rules): self
    {
        if (is_string($urls)) {
            $urls = [$urls];
        }

        $requests = new RequestCollection;
        foreach ($urls as $url) {
            $requests->add(new Request('GET', $url));
        }

        $this->request($requests, function ($html) use ($rangeSelector, $rules) {
            $data = $this->query($html, $rules, $rangeSelector);
            $this->list = array_merge($this->list, $data);
        });

        return $this;
    }

    /**
     * Filter $this->lists by closure or FilterAbstract, and slice
     *
     * @param  ?int  $slice  max length of list
     * @return self
     */
    public function filterList(FilterAbstract|Closure $filter, ?int $slice = null): static
    {
        $existUrls = [];

        foreach ($this->list as $key => $item) {
            // unique by item link
            if (in_array($item['link'], $existUrls)) {
                unset($this->list[$key]);

                continue;
            }
            $existUrls[] = $item['link'];

            // customize function
            if (is_callable($filter) ? $filter($item) : $filter->filter($item)) {
                unset($this->list[$key]);
            }
        }
        // cut array by @slice
        if ($slice) {
            $this->list = array_slice(array_values($this->list), 0, $slice);
        }

        return $this;
    }

    /**
     * Crawler Detail Data
     *
     * @param  RuleCollection  $rules  filter data rules
     * @param  SaveAbstract|Closure  $saveHandler  save data handler
     * @param  ?array  $appendData  append data to crawled before save
     */
    public function crawlerData(
        RuleCollection $rules,
        SaveAbstract|Closure $saveHandler,
        ?string $rangeSelector = null,
        ?array $appendData = null,
        ?Closure $closure = null
    ): void {
        $this->list = array_values($this->list);

        $progressBar = $this->output?->createProgressBar(count($this->list));
        $progressBar?->start();

        $requests = new RequestCollection;
        foreach ($this->list as $item) {
            $requests->add(new Request('GET', $item['link']));
        }

        $this->request($requests, function ($html, int $index) use ($rules, $rangeSelector, $appendData, $saveHandler, $closure, $progressBar) {
            $listData = $this->list[$index];
            $data = array_merge($listData, $this->query($html, $rules, $rangeSelector));

            if ($appendData) {
                $data = array_merge($data, $appendData);
            }

            if ($closure) {
                $data = $closure($data);
            }

            if ($saveHandler instanceof SaveAbstract) {
                $saveHandler->save($data, $listData['link']);
            } else {
                $saveHandler($data);
            }
            $progressBar?->advance();
        }, function () use ($progressBar) {
            $progressBar?->advance();
        });

        $progressBar?->finish();
        $this->output?->newLine();
    }
}
