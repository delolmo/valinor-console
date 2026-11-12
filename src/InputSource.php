<?php

declare(strict_types=1);

namespace DelOlmo\Valinor\Mapper\Source;

use IteratorAggregate;
use Override;
use Symfony\Component\Console\Input\InputInterface as Input;
use Traversable;

use function array_replace;

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

        // PHP stores a numeric name such as "1" as an int key; Valinor reads keys as names, so yield strings.
        foreach (array_replace($input->getOptions(), $input->getArguments()) as $name => $value) {
            yield (string) $name => $value;
        }
    }
}
