<?php

/**
 * Copyright (C) GrizzIT, Inc. All rights reserved.
 * See LICENSE for license details.
 */

namespace GrizzIt\Fallback\Common;

interface FallbackStackInterface extends FallbackInterface
{
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
    ): void;
}
