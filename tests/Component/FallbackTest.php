<?php

/**
 * Copyright (C) GrizzIT, Inc. All rights reserved.
 * See LICENSE for license details.
 */

namespace GrizzIt\Fallback\Tests\Component;

use PHPUnit\Framework\TestCase;
use GrizzIt\Fallback\Component\Fallback;
use GrizzIt\Fallback\Exception\FallbackFailedException;
use GrizzIt\Validator\Common\ValidatorInterface;

/**
 * @coversDefaultClass GrizzIt\Fallback\Component\Fallback
 * @covers GrizzIt\Fallback\Exception\FallbackException
 * @covers GrizzIt\Fallback\Exception\FallbackFailedException
 */
class FallbackTest extends TestCase
{
    /**
     * @return void
     *
     * @covers ::__construct
     * @covers ::__invoke
     */
    public function testFallback(): void
    {
        $subject = new class {
            /**
             * Returns the input.
             *
             * @return string
             */
            public function foo(string $input): string
            {
                return $input;
            }
        };

        $validator = $this->createMock(ValidatorInterface::class);

        $validator->expects(static::once())
                  ->method('__invoke')
                  ->with('foo')
                  ->willReturn(true);

        $fallback = new Fallback([$subject, 'foo'], $validator, 'foo');

        $this->assertEquals('foo', $fallback->__invoke('bar'));
    }

    /**
     * @return void
     *
     * @covers ::__construct
     * @covers ::__invoke
     */
    public function testFallbackFailed(): void
    {
        $subject = new class {
            /**
             * Returns the input.
             *
             * @return string
             */
            public function foo(string $input): string
            {
                return $input;
            }
        };

        $validator = $this->createMock(ValidatorInterface::class);

        $validator->expects(static::once())
                  ->method('__invoke')
                  ->with('foo')
                  ->willReturn(false);

        $fallback = new Fallback([$subject, 'foo'], $validator, 'foo');

        $this->expectException(FallbackFailedException::class);
        $fallback->__invoke('bar');
    }
}
