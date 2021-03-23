<?php

namespace Differ\Differ;

use function Differ\Parsers\parse;
use function Differ\Formatters\format;
use function Functional\sort;

/**
 * @param string $key
 * @param string $type
 * @param mixed  $oldValue
 * @param mixed  $newValue
 * @param array  $children
 *
 * @return array
 */
function makeNode($key, $type, $oldValue, $newValue, $children = null)
{
    return [
        'key' => $key,
        'type' => $type,
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
                    return makeNode($key, 'added', null, $object2->$key);
                }
                if (!property_exists($object2, $key)) {
                    return makeNode($key, 'removed', $object1->$key, null);
                }
                if ($object1->$key === $object2->$key) {
                    return makeNode($key, 'unchanged', $object1->$key, $object2->$key);
                }
                if (is_object($object1->{$key}) && is_object($object2->$key)) {
                    return makeNode($key, 'complex', null, null, getDiff($object1->$key, $object2->$key));
                }
                return makeNode($key, 'updated', $object1->$key, $object2->$key);
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
