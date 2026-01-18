<?php

namespace App\Traits;

trait GeneratesAutoId
{
    /**
     * Generate next ID.
     */
    public static function generateAutoId(): int
    {
        $instance = new static;
        $primaryKey = $instance->getKeyName() ?? 'id';
        $maxId = static::max($primaryKey);
        return ($maxId ?? 0) + 1;
    }
}
