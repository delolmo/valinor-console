<?php

declare(strict_types=1);

namespace DelOlmo\Valinor\Mapper\Source;

use ArrayObject;
use IteratorAggregate;
use Override;
use Symfony\Component\Console\Input\InputInterface as Input;
use Traversable;

use function array_merge;

/**
 * A Valinor source holding a console input's options and arguments. On a
 * shared name, the argument wins over the option.
 *
 * @implements IteratorAggregate<string, mixed>
 */
final readonly class InputSource implements IteratorAggregate
{
    public function __construct(
        private Input $input,
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
