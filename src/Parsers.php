<?php

namespace Differ\Parsers;

use Symfony\Component\Yaml\Yaml;

function parse(string $filePath): object
{
    $fileExtension = pathinfo($filePath, PATHINFO_EXTENSION);
    $content = file_get_contents($filePath) ? file_get_contents($filePath) : '';

    switch ($fileExtension) {
        case 'json':
            return json_decode($content);
        case 'yml':
        case 'yaml':
            return Yaml::parse($content, Yaml::PARSE_OBJECT_FOR_MAP);
        default:
            throw new \Exception("File extension '$fileExtension' is not supported");
    }
}
