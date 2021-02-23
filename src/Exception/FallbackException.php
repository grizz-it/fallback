<?php

/**
 * Copyright (C) GrizzIT, Inc. All rights reserved.
 * See LICENSE for license details.
 */

namespace GrizzIt\Fallback\Exception;

use Exception;
use Throwable;

class FallbackException extends Exception
{
    /**
     * Constructor
     *
     * @param string    $message
     * @param int       $code
     * @param Throwable $previous
     */
    public function __construct(
        string $message = '',
        int $code = 0,
        Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
}
