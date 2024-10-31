<?php

namespace App\Http\Helpers;

class GameHelpers
{
    public static function swap(&$a, &$b)
    {
        [$a, $b] = [$b, $a];
    }

    public static function generateRandomFloat($lower, $upper)
    {
        return mt_rand() / mt_getrandmax() * ($upper - $lower) + $lower;
    }
}
