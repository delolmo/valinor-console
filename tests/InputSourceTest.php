<?php

declare(strict_types=1);

namespace DelOlmo\Valinor\Mapper\Source\Tests;

use CuyZ\Valinor\Mapper\Source\Source;
use CuyZ\Valinor\MapperBuilder;
use DelOlmo\Valinor\Mapper\Source\InputSource;
use DelOlmo\Valinor\Mapper\Source\Tests\Fixtures\GreetCommandInput;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputOption;

use function iterator_to_array;

#[CoversClass(InputSource::class)]
final class InputSourceTest extends TestCase
{
    #[TestDox('getInput() returns the input the source was built from')]
    public function testGetInputReturnsTheWrappedInput(): void
    {
        $input = new ArrayInput([]);

        $inputSource = new InputSource($input);

        Assert::assertSame($input, $inputSource->getInput());
    }

    /**
     * @param array<string, mixed> $parameters
     * @param array<string, mixed> $expected
     */
    #[DataProvider('inputProvider')]
    #[TestDox('getIterator() yields $_dataName')]
    public function testGetIteratorMergesOptionsAndArguments(array $parameters, array $expected): void
    {
        $definition = new InputDefinition([
            new InputArgument('name', InputArgument::OPTIONAL),
            new InputOption('name', mode: InputOption::VALUE_REQUIRED),
            new InputOption('times', mode: InputOption::VALUE_REQUIRED),
        ]);

        $input = new ArrayInput($parameters, $definition);

        $values = iterator_to_array(new InputSource($input));

        Assert::assertSame($expected, $values);
    }

    /** @return iterable<string, array{array<string, mixed>, array<string, mixed>}> */
    public static function inputProvider(): iterable
    {
        yield 'the argument over an option of the same name' => [
            ['name' => 'argument', '--name' => 'option'],
            ['name' => 'argument', 'times' => null],
        ];

        yield 'the option when the argument is not given' => [
            ['--name' => 'option', '--times' => '3'],
            ['name' => null, 'times' => '3'],
        ];
    }

    #[TestDox('A Valinor mapper maps the options and arguments onto an object')]
    public function testValinorMapsTheSource(): void
    {
        $definition = new InputDefinition([
            new InputArgument('name', InputArgument::REQUIRED),
            new InputOption('times', mode: InputOption::VALUE_REQUIRED, default: '1'),
            new InputOption('shout', mode: InputOption::VALUE_NONE),
        ]);

        $input = new ArrayInput(['name' => 'Ada', '--times' => '2', '--shout' => true], $definition);

        $greetCommandInput = new MapperBuilder()
            ->allowScalarValueCasting()
            ->mapper()
            ->map(GreetCommandInput::class, Source::iterable(new InputSource($input)));

        Assert::assertEquals(new GreetCommandInput(name: 'Ada', times: 2, shout: true), $greetCommandInput);
    }
}
