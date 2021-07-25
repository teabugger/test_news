<?php

declare(strict_types=1);

class StringHelper
{
    public static function isInteger(string $string): bool
    {
        if (is_numeric($string)) {
            return $string === (string) (int) $string;
        }

        return false;
    }

    public static function isNonNegativeInteger(string $string): bool
    {
        return self::isInteger($string) && (int) $string >= 0;
    }

    public static function isPositiveInteger(string $string): bool
    {
        return self::isInteger($string) && (self::isNonNegativeInteger($string) || 0 === (int) $string);
    }
}
