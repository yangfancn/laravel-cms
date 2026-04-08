<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('app:test')]
#[Description('Test Command')]
class Test extends Command
{
    /**
     * Execute the console command.
     */
    public function handle() {}
}
