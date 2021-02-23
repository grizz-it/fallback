<?php

/**
 * Copyright (C) GrizzIT, Inc. All rights reserved.
 * See LICENSE for license details.
 */

namespace GrizzIt\Fallback\Tests\Component;

use PHPUnit\Framework\TestCase;
use GrizzIt\Fallback\Common\FallbackInterface;
use GrizzIt\Fallback\Component\FallbackStack;
use GrizzIt\Fallback\Exception\FallbackStackFailedException;
use GrizzIt\Validator\Common\ValidatorInterface;

/**
 * @coversDefaultClass GrizzIt\Fallback\Component\FallbackStack
 * @covers GrizzIt\Fallback\Exception\FallbackException
 * @covers GrizzIt\Fallback\Exception\FallbackStackFailedException
 * @covers GrizzIt\Fallback\Exception\FallbackValidationFailedException
 */
class FallbackStackTest extends TestCase
{
    /**
     * @return void
     *
     * @covers ::__construct
     * @covers ::addFallback
     * @covers ::__invoke
     */
    public function testFallbackStack(): void
    {
        $parameters = ['foo', 'bar', 'baz'];
        $fallback = $this->createMock(FallbackInterface::class);
        $fallbackTwo = $this->createMock(FallbackInterface::class);
        $fallbackThree = $this->createMock(FallbackInterface::class);
        $validator = $this->createMock(ValidatorInterface::class);

        $stack = new FallbackStack($validator);

        $stack->addFallback($fallbackTwo, 1);
        $stack->addFallback($fallbackThree, 2);
        $stack->addFallback($fallback, 0);

        $fallback->expects(static::once())
                 ->method('__invoke')
                 ->with(...$parameters)
                 ->willReturn(1);

        $fallbackTwo->expects(static::once())
                    ->method('__invoke')
                    ->with(...$parameters)
                    ->willReturn('foo');

        $fallbackThree->expects(static::never())
                      ->method('__invoke');

        $validator->expects(static::exactly(2))
                  ->method('__invoke')
                  ->withConsecutive([1], ['foo'])
                  ->willReturnOnConsecutiveCalls(false, true);

        $this->assertEquals(
            'foo',
            $stack->__invoke(...$parameters)
        );
    }

    /**
     * @return void
     *
     * @covers ::__construct
     * @covers ::addFallback
     * @covers ::__invoke
     */
    public function testFallbackStackFailed(): void
    {
        $parameters = ['foo', 'bar', 'baz'];
        $fallback = $this->createMock(FallbackInterface::class);
        $fallbackTwo = $this->createMock(FallbackInterface::class);
        $fallbackThree = $this->createMock(FallbackInterface::class);
        $validator = $this->createMock(ValidatorInterface::class);

        $stack = new FallbackStack($validator);

        $stack->addFallback($fallbackTwo, 1);
        $stack->addFallback($fallbackThree, 2);
        $stack->addFallback($fallback, 0);

        $fallback->expects(static::once())
                 ->method('__invoke')
                 ->with(...$parameters)
                 ->willReturn(1);

        $fallbackTwo->expects(static::once())
                    ->method('__invoke')
                    ->with(...$parameters)
                    ->willReturn(2);

        $fallbackThree->expects(static::once())
                      ->method('__invoke')
                      ->with(...$parameters)
                      ->willReturn(3);

        $validator->expects(static::exactly(3))
                  ->method('__invoke')
                  ->withConsecutive([1], [2], [3])
                  ->willReturn(false);

        $this->expectException(FallbackStackFailedException::class);
        $stack->__invoke(...$parameters);
    }
}
