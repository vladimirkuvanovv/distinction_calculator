<?php

namespace Gendiff\Parsers;

use Symfony\Component\Yaml\Yaml;

function getDecodedProperties($extension, $content)
{
    switch ($extension) {
        case 'json':
            return json_decode($content, true);
        case 'yml':
            return Yaml::parse($content);
        default:
            throw new \Exception('unknown files');
    }
}

function getContent($pathToFile)
{
    $realPath = realpath($pathToFile);

    if (!$realPath) {
        throw new \Exception('wrong file path');
    }

    return file_get_contents($realPath);
}
