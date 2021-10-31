<?php

declare(strict_types=1);

namespace Spatie\QueueAware\Tests\Doubles\Singletons;

use PHPUnit\Framework\Assert;

final class TestableSingleton
{
    public function __construct(
        public string $identifier
    ) {
    }

    public function assertHasIdentifier(string $identifier): self
    {
        Assert::assertSame($identifier, $this->identifier);

        return $this;
    }
}
