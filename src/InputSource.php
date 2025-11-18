<?php

declare(strict_types=1);

namespace DelOlmo\Valinor\Mapper\Source;

use ArrayObject;
use IteratorAggregate;
use Override;
use Symfony\Component\Console\Input\InputInterface as Input;
use Traversable;

use function array_merge;

/** @implements IteratorAggregate<string, mixed> */
final class InputSource implements IteratorAggregate
{
    public function __construct(
        private readonly Input $input,
    ) {
    }

    public function getInput(): Input
    {
        return $this->input;
    }

    /** @return Traversable<string, mixed> */
    #[Override]
    public function getIterator(): Traversable
    {
        $input = $this->input;

        $array = array_merge($input->getOptions(), $input->getArguments());

        return new ArrayObject($array);
    }
}
