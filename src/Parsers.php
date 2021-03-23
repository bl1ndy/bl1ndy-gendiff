<?php

namespace Differ\Parsers;

use Symfony\Component\Yaml\Yaml;

function parse(string $filePath): object
{
    if (!$filePath) {
        throw new \Exception("Invalid filepath '$filePath'!");
    }

    $fileExtension = pathinfo($filePath, PATHINFO_EXTENSION);

    switch ($fileExtension) {
        case 'json':
            return json_decode(
                file_get_contents($filePath)
            );
        case 'yml':
        case 'yaml':
            return Yaml::parse(
                file_get_contents($filePath),
                Yaml::PARSE_OBJECT_FOR_MAP,
            );
        default:
            throw new \Exception("File extension '$fileExtension' is not supported");
    }
}
