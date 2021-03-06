<?php

/**
 * Copyright (C) GrizzIT, Inc. All rights reserved.
 * See LICENSE for license details.
 */

namespace GrizzIt\Fallback\Component;

use GrizzIt\Fallback\Common\FallbackInterface;
use GrizzIt\Fallback\Exception\FallbackFailedException;
use GrizzIt\Validator\Common\ValidatorInterface;
use GrizzIt\Validator\Component\Logical\AlwaysValidator;

class Fallback implements FallbackInterface
{
    /**
     * The callable which should be invoked when this class is invoked.
     *
     * @var callable
     */
    private $callable;

    /**
     * The validator for the return type of the invocation.
     *
     * @var ValidatorInterface;
     */
    private ValidatorInterface $validator;

    /**
     * The parameters which override the parameters passed through the invocation.
     *
     * @var array
     */
    private array $parameters;

    /**
     * Constructor
     *
     * @param callable           $callable
     * @param ValidatorInterface $validator
     * @param mixed           ...$parameters
     */
    public function __construct(
        callable $callable,
        ValidatorInterface $validator = null,
        mixed ...$parameters
    ) {
        $this->callable   = $callable;
        $this->validator  = $validator ?? new AlwaysValidator(true);
        $this->parameters = $parameters;
    }

    /**
     * Calls the callable and returns the result.
     *
     * @param mixed ...$parameters
     *
     * @return mixed
     *
     * @throws FallbackFailedException When none of the callables return a valid response.
     */
    public function __invoke(mixed ...$parameters): mixed
    {
        $parameters = $this->parameters + $parameters;
        $return = ($this->callable)(...$parameters);
        if (($this->validator)($return)) {
            return $return;
        }

        throw new FallbackFailedException($this->callable, $parameters);
    }
}
