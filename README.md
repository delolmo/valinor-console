# delolmo/valinor-console

[![Packagist Version](https://img.shields.io/packagist/v/delolmo/valinor-console.svg?style=flat-square)](https://packagist.org/packages/delolmo/valinor-console)
[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://opensource.org/licenses/MIT)

Use the `InputInterface` of [symfony/console](https://github.com/symfony/console) as a source for the [cuyz/valinor](https://github.com/cuyz/valinor) mapper.

## Requirements

* PHP ~8.4.27 || ^8.5.12
* cuyz/valinor ^2.6
* symfony/console ^8.0

## Installation

```sh
composer require delolmo/valinor-console
```

## Example

```php
use App\DTO\CustomObject;
use CuyZ\Valinor\Mapper\Source\Source;
use CuyZ\Valinor\MapperBuilder;
use DelOlmo\Valinor\Mapper\Source\InputSource;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class CustomCommand extends Command
{
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        // Create the source from the input
        $source = Source::iterable(new InputSource($input));

        // Create the mapper, using the desired configuration
        $mapper = new MapperBuilder()
            ->allowSuperfluousKeys()
            ->allowScalarValueCasting()
            ->mapper();

        // Map the source to whatever object makes sense
        $mapped = $mapper->map(CustomObject::class, $source);

        // Apply whatever business logic makes sense from here
        // ...

        return Command::SUCCESS;
    }
}
```

## Final notes

- Major versions of `delolmo/valinor-console` follow the major versions of `cuyz/valinor`.
- Symfony adds several options to every command's input (`help`, `quiet`, `verbose` and others). Unless the target object declares them, the mapper needs `allowSuperfluousKeys()`, or mapping fails.
- An argument and an option may share a name. The source then holds the argument's value.
- Console values arrive as strings (or arrays of strings), so the mapper usually needs `allowScalarValueCasting()`. See the [Valinor documentation](https://valinor.cuyz.io/latest/) on type strictness.
