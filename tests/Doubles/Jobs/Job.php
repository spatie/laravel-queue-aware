<?php

declare(strict_types=1);

namespace Spatie\QueueAware\Tests\Doubles\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Symfony\Component\Console\Command\Command;
use Throwable;

abstract class Job implements ShouldQueue
{
    use InteractsWithQueue;

    public function handle(): int
    {
        $cacheKey = 'job-result-'.class_basename(static::class);

        try {
            $result = app()->call([$this, 'handleJob']);
            cache()->forever($cacheKey, $result !== Command::FAILURE);
        } catch (Throwable $exception) {
            cache()->forever($cacheKey, $exception);

            return Command::FAILURE;
        }

        return $result;
    }
}
