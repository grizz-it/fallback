<?php

/**
 * Copyright (C) GrizzIT, Inc. All rights reserved.
 * See LICENSE for license details.
 */

namespace GrizzIt\Fallback\Common;

interface FallbackInterface
{
    /**
     * Calls the callable and returns the result.
     *
     * @param mixed ...$parameters
     *
     * @return mixed
     */
    public function __invoke(mixed ...$parameters): mixed;
}
