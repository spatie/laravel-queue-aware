<?php

declare(strict_types=1);

namespace Spatie\QueueAware\Tests\Doubles\QueueAwareables;

use Spatie\QueueAware\Contracts\MakesQueueAware;
use Spatie\QueueAware\Tests\Doubles\Singletons\TestableSingleton;

final class ImplicitMakesTestableSingletonQueueAware implements MakesQueueAware
{
    public function getContainerKey(): string
    {
        return TestableSingleton::class;
    }

    public function shouldBeAware(object $queueable): bool
    {
        return true;
    }

    public function dehydrate(): mixed
    {
        return app(TestableSingleton::class)->identifier;
    }

    public function hydrate($data): mixed
    {
        app()->instance($this->getContainerKey(), new TestableSingleton($data));

        return null;
    }
}
