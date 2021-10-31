<?php

declare(strict_types=1);

namespace Spatie\QueueAware\Support;

use Closure;
use Illuminate\Support\Traits\ReflectsClosures;
use Spatie\QueueAware\Contracts\MakesQueueAware;

final class AnonymousMakesQueueAware implements MakesQueueAware
{
    use ReflectsClosures;

    public function __construct(
        private string $containerKey,
        public Closure $dehydrator,
        public Closure $hydrator,
    ) {
    }

    public function getContainerKey(): string
    {
        return $this->containerKey;
    }

    public function shouldBeAware(object $queueable): bool
    {
        return true;
    }

    public function dehydrate(): mixed
    {
        return app()->call($this->dehydrator);
    }

    public function hydrate($data, $event): mixed
    {
        $parameters = array_keys($this->closureParameterTypes($this->hydrator));

        return app()->call($this->hydrator, [$parameters[0] => $data]);
    }
}
