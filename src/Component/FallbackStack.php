<?php
/**
 * Copyright (C) GrizzIT, Inc. All rights reserved.
 * See LICENSE for license details.
 */

namespace GrizzIt\Fallback\Component;

use GrizzIt\Fallback\Common\FallbackStackInterface;
use GrizzIt\Fallback\Common\FallbackInterface;
use GrizzIt\Fallback\Exception\FallbackException;
use GrizzIt\Fallback\Exception\FallbackStackFailedException;
use GrizzIt\Fallback\Exception\FallbackValidationFailedException;
use GrizzIt\Validator\Common\ValidatorInterface;
use GrizzIt\Validator\Component\Logical\AlwaysValidator;

class FallbackStack implements FallbackStackInterface
{
    /**
     * Contains the stack return validator.
     *
     * @var ValidatorInterface
     */
    private $validator;

    /**
     * Contains the fallbacks.
     *
     * @var FallbackInterface;
     */
    private $fallback;

    /**
     * Constructor
     *
     * @param ValidatorInterface $validator
     */
    public function __construct(ValidatorInterface $validator = null)
    {
        $this->validator = $validator ?? new AlwaysValidator(true);
    }

    /**
     * Adds a fallback to the fallback stack.
     *
     * @param FallbackInterface $fallback
     * @param integer           $position
     *
     * @return void
     */
    public function addFallback(
        FallbackInterface $fallback,
        int $position = 0
    ): void {
        $this->fallback[] = [
            'fallback' => $fallback,
            'position' => $position
        ];
    }

    /**
     * Invokes the stack in order, untill a valid result is returned.
     *
     * @param mixed ...$parameters
     *
     * @return mixed
     *
     * @throws FallbackValidationFailedException When the return is invalid.
     * @throws FallbackStackFailedException When the entire stack fails.
     */
    public function __invoke(...$parameters)
    {
        usort($this->fallback, function (array $left, array $right): int {
            return $left['position'] <=> $right['position'];
        });
        $fallbacks = array_column($this->fallback, 'fallback');
        $fallbackFailures = [];
        foreach ($fallbacks as $fallback) {
            try {
                $return = $fallback(...$parameters);
                if (!($this->validator)($return)) {
                    throw new FallbackValidationFailedException(
                        $this->validator,
                        $return
                    );
                }

                return $return;
            } catch (FallbackException $exception) {
                $fallbackFailures[] = $exception;
            }
        }

        throw new FallbackStackFailedException($fallbackFailures);
    }
}
