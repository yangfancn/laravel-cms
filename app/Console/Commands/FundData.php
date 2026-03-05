<?php

namespace App\Console\Commands;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Console\Command;
use Symfony\Component\Console\Command\Command as CommandAlias;
use Symfony\Component\Console\Helper\TableSeparator;

class FundData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:fund-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $funds = ['fu_017835', 'fu_014767', 'fu_016873', 'sz163208', 'fu_019828', 'fu_000217'];
        $url = 'https://hq.sinajs.cn/list='.implode(
            ',',
            $funds
        );

        $client = new Client([
            'headers' => [
                'Referer' => 'https://finance.sina.com.cn/',
            ],
        ]);

        try {
            $response = $client
                ->get($url)
                ->getBody()
                ->getContents();
        } catch (GuzzleException $e) {
            $this->error($e->getMessage());

            return CommandAlias::FAILURE;
        }
        $items = $response
            |> trim(...)
            |> (fn ($str) => mb_convert_encoding($str, 'UTF-8', 'GBK'))
            |> (fn ($str) => explode("\n", $str));

        $data = collect($items)->map(function (string $item) {
            return $item
                |> (fn ($str) => str_replace(['var hq_str_', '="', '";'], ['', ',', ''], $str))
                |> (fn ($str) => explode(',', $str))
                |> $this->adapt(...);
        });

        // split
        $rows = $data->flatMap(fn ($row, $i) => $i ? [new TableSeparator, $row] : [$row]);

        $this->table(
            ['代码', '名称', '昨收', '估值', '涨幅', '更新时间'],
            $rows,
            'box-double'
        );
    }

    private function adapt(array $data): array
    {
        $code = $data[0];

        if (str_starts_with($code, 'fu_')) {
            $result = [
                $code,
                $data[1],
                $data[4],
                "$data[3]",
                round(($data[3] - $data[4]) * 100 / $data[4], 2),
                "$data[8] $data[2]",
            ];
        }
        if (str_starts_with($code, 'sz')) {
            $result = [
                $code,
                $data[1],
                $data[3],
                $data[4],
                round(($data[4] - $data[3]) * 100 / $data[3], 2),
                "$data[31] $data[32]",
            ];
        }

        $result[4] = $this->colorizePercent($result[4]);

        return $result;
    }

    private function colorizePercent(float $pct): string
    {
        if ($pct >= 0) {
            return "<fg=bright-red>{$pct}%</>";
        }
        if ($pct < 0) {
            return "<fg=bright-green>{$pct}%</>";
        }

        return "{$pct}%";
    }
}
