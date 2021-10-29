<?php

namespace Spatie\QueueAware\Commands;

use Illuminate\Console\Command;

class QueueAwareCommand extends Command
{
    public $signature = 'laravel-queue-aware';

    public $description = 'My command';

    public function handle()
    {
        $this->comment('All done');
    }
}
