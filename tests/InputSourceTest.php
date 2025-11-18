<?php

declare(strict_types=1);

namespace DelOlmo\Valinor\Mapper\Source;

use ArrayObject;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Input\InputInterface;

final class InputSourceTest extends TestCase
{
    public function testGetInput(): void
    {
        $input = $this->createMock(InputInterface::class);

        $inputSource = new InputSource($input);

        Assert::assertSame($input, $inputSource->getInput());
    }

    public function testGetIterator(): void
    {
        $options   = ['foo' => 'bar'];
        $arguments = ['foo' => 'baz'];

        $inputx1 = $this->createMock(InputInterface::class);
        $inputx1->method('getOptions')->willReturn($options);
        $inputx1->method('getArguments')->willReturn($arguments);

        $sourcex1   = new InputSource($inputx1);
        $expectedx1 = new ArrayObject(['foo' => 'baz']);
        Assert::assertEquals($expectedx1, $sourcex1->getIterator());

        $inputx2 = $this->createMock(InputInterface::class);
        $inputx2->method('getOptions')->willReturn($options);

        $sourcex2   = new InputSource($inputx2);
        $expectedx2 = new ArrayObject(['foo' => 'bar']);
        Assert::assertEquals($expectedx2, $sourcex2->getIterator());

        $inputx3 = $this->createMock(InputInterface::class);
        $inputx3->method('getArguments')->willReturn($arguments);

        $sourcex3   = new InputSource($inputx3);
        $expectedx3 = new ArrayObject(['foo' => 'baz']);
        Assert::assertEquals($expectedx3, $sourcex3->getIterator());
    }
}
