<?php

namespace Differ\Differ;

use function Differ\Parsers\parse;
use function Differ\Formatters\format;
use function Functional\sort;

/**
 * @param string $type
 * @param string $key
 * @param mixed  $oldValue
 * @param mixed  $newValue
 * @param array  $children
 *
 * @return array
 */
function getNode($type, $key, $oldValue, $newValue, $children = [])
{
    return [
        'type' => $type,
        'key' => $key,
        'oldValue' => $oldValue,
        'newValue' => $newValue,
        'children' => $children
    ];
}

function getDiff(object $object1, object $object2): array
{
    $arr1 = get_object_vars($object1);
    $arr2 = get_object_vars($object2);
    $keys = sort(
        array_keys(array_merge($arr1, $arr2)),
        fn ($left, $right) => strcmp($left, $right)
    );

    $diff = array_values(
        array_map(
            function ($key) use ($object1, $object2): array {
                if (!property_exists($object1, $key)) {
                    return getNode('added', $key, null, $object2->{$key});
                }
                if (!property_exists($object2, $key)) {
                    return getNode('removed', $key, $object1->{$key}, null);
                }
                if ($object1->{$key} === $object2->{$key}) {
                    return getNode('unchanged', $key, $object1->{$key}, $object2->{$key});
                }
                if (is_object($object1->{$key}) && is_object($object2->{$key})) {
                    return getNode('complex', $key, null, null, getDiff($object1->{$key}, $object2->{$key}));
                }
                return getNode('changed', $key, $object1->{$key}, $object2->{$key});
            },
            $keys
        )
    );

    return $diff;
}

/**
 * @param string $firstFilePath
 * @param string $secondFilePath
 * @param string $format
 *
 * @return string
 */
function genDiff($firstFilePath, $secondFilePath, $format = 'stylish')
{
    $firstFile = parse($firstFilePath);
    $secondFile = parse($secondFilePath);

    $diff = getDiff($firstFile, $secondFile);

    return format($diff, $format);
}
