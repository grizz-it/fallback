<?php

/**
 * Copyright (C) GrizzIT, Inc. All rights reserved.
 * See LICENSE for license details.
 */

namespace GrizzIt\Fallback\Exception;

use GrizzIt\Validator\Common\ValidatorInterface;

class FallbackValidationFailedException extends FallbackException
{
    /**
     * Constructor.
     *
     * @param ValidatorInterface $callable
     * @param mixed              $return
     */
    public function __construct(ValidatorInterface $validator, mixed $return)
    {
        parent::__construct(
            sprintf(
                'Callback stack validator failed for %s with %s.',
                gettype($return),
                get_class($validator)
            )
        );
    }
}
