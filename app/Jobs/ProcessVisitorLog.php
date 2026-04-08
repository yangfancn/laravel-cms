<?php

namespace App\Jobs;

use App\Models\Bot;
use App\Models\Visitor;
use DeviceDetector\ClientHints;
use DeviceDetector\DeviceDetector;
use DeviceDetector\Parser\AbstractParser;
use DeviceDetector\Parser\Device\AbstractDeviceParser;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Symfony\Component\HttpFoundation\ServerBag;

class ProcessVisitorLog implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public ?int $userId,
        public string $path,
        public string $userAgent,
        public string $ip,
        public ServerBag $serverBag,
        public ?string $modelClassName,
        public ?int $modelId = null,
    ) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        AbstractDeviceParser::setVersionTruncation(AbstractParser::VERSION_TRUNCATION_NONE);
        $client = new DeviceDetector($this->userAgent, ClientHints::factory($this->serverBag->all()));
        $client->parse();
        if ($client->isBot()) {
            $bot = $client->getBot()['name'];
            $botName = null;
            foreach (['baidu', 'bing', 'duckduckgo', 'google', 'yandex'] as $item) {
                if (str_contains(strtolower($bot), $item)) {
                    $botName = $item;
                    break;
                }
            }

            if (! $botName) {
                $botName = 'other';
            }

            $botModel = Bot::whereDate('created_at', now())->firstOrCreate();
            $botModel->$botName += 1;
            $botModel->save();
        } else {
            $ipInfo = geoip($this->ip);
            Visitor::create([
                'visitable_type' => $this->modelClassName,
                'visitable_id' => $this->modelId,
                'user_id' => $this->userId,
                'path' => $this->path,
                'os' => $client->getOs('name'),
                'browser' => $client->getClient('name'),
                'user_agent' => $this->userAgent,
                'ip' => $this->ip,
                'country' => $ipInfo->country,
                'city' => $ipInfo->city,
            ]);
        }
    }
}
