<?php

namespace App\Gendiff;

use Symfony\Component\Yaml\Yaml;

function parseJson($content)
{
    return json_decode($content, true);
}

function parseYaml($content)
{
    return Yaml::parse($content);
}

function getContent($pathToFile)
{
    $realPath = realpath($pathToFile);

    if (!$realPath) {
        throw new \Exception("Wrong file path {$pathToFile}");
    }

    if (!file_exists($realPath)) {
        throw new \Exception("File {$realPath} does not exist");
    }

    if (is_dir($realPath)) {
        throw new \Exception("{$realPath} is directory");
    }

    return file_get_contents($realPath);
}
