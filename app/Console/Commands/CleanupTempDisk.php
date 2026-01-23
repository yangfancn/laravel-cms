<?php

namespace App\Console\Commands;

use \DirectoryIterator;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class CleanupTempDisk extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:cleanup-temp-disk {--days=5}';

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
        $days = (int) $this->option('days');
        $expiredAt = now()->subDays($days)->timestamp;

        $disk = Storage::disk('temp');
        $iterator = new DirectoryIterator($disk->path(''));

        foreach ($iterator as $item) {
            if ($item->isDot()) {
                continue;
            }

            if ($item->getMTime() <= $expiredAt) {
                $item->isFile()
                    ? $disk->delete($item->getFilename())
                    : $disk->deleteDirectory($item->getFilename());
            }
        }
    }
}
