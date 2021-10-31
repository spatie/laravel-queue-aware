<?php

declare(strict_types=1);

namespace Spatie\QueueAware\Tests\Doubles\Jobs;

use Spatie\QueueAware\Tests\Doubles\Singletons\TestableSingleton;
use Symfony\Component\Console\Command\Command;

final class AwareJob extends Job
{
    public function __construct(
        private string $expectedIdentifier
    ) {
    }

    public function handleJob(TestableSingleton $singleton)
    {
        $singleton->assertHasIdentifier($this->expectedIdentifier);

        return Command::SUCCESS;
    }
}
