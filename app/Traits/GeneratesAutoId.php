<?php

namespace App\Traits;

trait GeneratesAutoId
{
    /**
     * Generate next ID.
     */
    public static function generateAutoId(): int
    {
        $maxId = static::max('id');
        return ($maxId ?? 0) + 1;
    }
}
