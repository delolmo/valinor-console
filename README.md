The following library allows using `InputInterface` objects of the [symfony/console](https://github.com/symfony/console) component as a source for the [cuyz/valinor](https://github.com/cuyz/valinor) library.

## Installation

```bash
composer require delolmo/valinor-console
```

## Example

```php

use App\DTO\CustomObject;
use CuyZ\Valinor\Mapper\Source\Source;
use CuyZ\Valinor\MapperBuilder;
use DelOlmo\Valinor\Mapping\Source\InputSource;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class CustomCommand extends Command
{
    public function execute(InputInterface $input, OutputInterface $output): int
    {
        // Create the Source using the new InputSource
        $source = Source::iterable(new InputSource($input));
        
        // Create the Mapper, using the desired configuration
        $mapper = new MapperBuilder())
            ->allowSuperfluousKeys()
            ->enableFlexibleCasting()
            ->mapper();
            
        // Map the source to whatever object makes sense
        $mapped = $mapper->map(CustomObject::class, $source);
        
        // Apply whatever business logic makes sense from here
        // ...
    }
}
```

## Final notes

- Versioning of `delolmo/valinor-console` will always match `cuyz/valinor` versions. Same goes for PHP versions.
- When creating the Mapper object, it should always be taken into account that, by default, Symfony adds several options to the InputInterface object (i.e., help or verbosity levels). If `allowSuperfluousKeys` is not used, the mapping process will throw an exception - unless you consider these parameters in the object you are trying to map (`App\DTO\CustomObject` in the above example). See [Allow superflous keys](https://valinor.cuyz.io/latest/mapping/type-strictness/#allowing-superfluous-keys) for more information.
- Although options and arguments cannot share the same name within the same Symfony command, it should be noted that, from an InputSource standpoint, arguments always take precedence over options. That is, if there is an argument and an option sharing the same name, InputSource will only use the argument's value for mapping purposes.
- Considering that Symfony command applications convert most of the fields to either strings or arrays, it is interesting to note that `enableFlexibleCasting` should also be configured in the Mapper. See [Enabling flexible casting](https://valinor.cuyz.io/latest/mapping/type-strictness/#enabling-flexible-casting) for more information.
