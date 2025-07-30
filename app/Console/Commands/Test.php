<?php

namespace App\Console\Commands;

use App\Services\Crawler\ImageConfig;
use App\Services\Crawler\ImageDownload;
use Illuminate\Console\Command;

class Test extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle() {
        $downloader = new ImageDownload(new ImageConfig(maxWidth: 600));

        $url = 'https://qiniu-images.php-blog.cn/images/20250630/gdBdJ4ZrzG3YIrib5oTXjADFHBwZn7rCMd6UdQ3i.jpg';

        $result = $downloader->download($url);
        dd($result);
    }
}
