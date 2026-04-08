<?php

namespace App\Console\Commands;

use DirectoryIterator;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

#[Signature('app:cleanup-temp-disk {--days=5}')]
#[Description('Cleanup Uploaded Temp Dir')]
class CleanupTempDisk extends Command
{
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
