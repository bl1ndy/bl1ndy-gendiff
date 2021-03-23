<?php

namespace Differ\Formatters\Plain;

use function Functional\flatten;

function formatValue($value): string
{
    $typeFormats = [
        'string' => fn($value) => "'$value'",
        'integer' => fn($value) => (string) $value,
        'object' => fn($value) => '[complex value]',
        'array' => fn($value) => '[complex value]',
        'boolean' => fn($value) => $value ? 'true' : 'false',
        'NULL' => fn($value) => 'null'
    ];

    $type = gettype($value);

    return $typeFormats[$type]($value);
}

function plain(array $diff, array $path = []): string
{
    $lines = array_map(
        function ($node) use ($path) {
            $fullPropertyPath = implode('.', [...$path, $node['key']]);
            switch ($node['type']) {
                case 'added':
                    $formattedValue = formatValue($node['newValue']);
                    return "Property '$fullPropertyPath' was added with value: {$formattedValue}";
                case 'removed':
                    $formattedValue = formatValue($node['oldValue']);
                    return "Property '$fullPropertyPath' was removed";
                case 'updated':
                    $oldValue = formatValue($node['oldValue']);
                    $newValue = formatValue($node['newValue']);
                    return "Property '$fullPropertyPath' was updated. From $oldValue to $newValue";
                case 'unchanged':
                    return [];
                case 'complex':
                    return plain($node['children'], [...$path, $node['key']]);
                default:
                    throw new \Exception("Node type '{$node['type']}' is invalid");
            }
        },
        $diff
    );

    return implode("\n", flatten($lines));
}
