<?php

namespace Differ\Formatters;

use function Differ\Formatters\Stylish\stylish;
use function Differ\Formatters\Plain\plain;
use function Differ\Formatters\Json\json;

function format($diff, $format): string
{
    switch ($format) {
        case 'plain':
            return plain($diff);
        case 'json':
            return json($diff);
        case 'stylish':
            return stylish($diff);
        default:
            throw new \Exception("Format '$format' is not supported");
    }
}
