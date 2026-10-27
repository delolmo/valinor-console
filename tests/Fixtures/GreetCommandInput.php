<?php

declare(strict_types=1);

namespace DelOlmo\Valinor\Mapper\Source\Tests\Fixtures;

final readonly class GreetCommandInput
{
    public function __construct(
        public string $name,
        public int $times,
        public bool $shout,
    ) {
    }
}
