<?php

namespace Differ\Formatters\Stylish;

use function Functional\flatten;

function getIndent($depth)
{
    return str_repeat('    ', $depth);
}

function formatValue($value, $depth)
{
    $formatComplexValue = function ($value, $depth) {
        $indent = getIndent($depth);
        $complexValue = array_map(
            function ($key, $value) use ($depth, $indent) {
                return "{$indent}    {$key}: " . formatValue($value, $depth);
            },
            array_keys($value),
            $value
        );

        return implode("\n", ["{", ...$complexValue, "{$indent}}"]);
    };

    $typeFormats = [
        'string' => fn($value) => $value,
        'integer' => fn($value) => (string) $value,
        'object' => fn($value) => $formatComplexValue(get_object_vars($value), $depth + 1),
        'array' => fn($value) => $formatComplexValue($value, $depth + 1),
        'boolean' => fn($value) => $value ? "true" : "false",
        'NULL' => fn($value) => 'null'
    ];

    $type = gettype($value);

    return $typeFormats[$type]($value);
}

function stylish(array $diff, int $depth = 0): string
{
    $indent = getIndent($depth);
    $lines = array_map(
        function ($node) use ($indent, $depth) {
            switch ($node['type']) {
                case 'added':
                    $formattedValue = formatValue($node['newValue'], $depth);
                    return "{$indent}  + {$node['key']}: {$formattedValue}";
                case 'removed':
                    $formattedValue = formatValue($node['oldValue'], $depth);
                    return "{$indent}  - {$node['key']}: {$formattedValue}";
                case 'updated':
                    $oldValue = formatValue($node['oldValue'], $depth);
                    $newValue = formatValue($node['newValue'], $depth);
                    return "{$indent}  - {$node['key']}: {$oldValue}"
                        . "\n" . "{$indent}  + {$node['key']}: {$newValue}";
                case 'unchanged':
                    $formattedValue = formatValue($node['newValue'], $depth);
                    return "{$indent}    {$node['key']}: {$formattedValue}";
                case 'complex':
                    $nestedIndent = getIndent($depth + 1);
                    return "{$nestedIndent}{$node['key']}: "
                        . stylish($node['children'], $depth + 1);
                default:
                    throw new \Exception("Node type '{$node['type']}' is invalid");
            }
        },
        $diff
    );

    return implode("\n", ["{", ...$lines, "{$indent}}"]);
}
