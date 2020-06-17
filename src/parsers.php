<?php

namespace App\Gendiff;

use Symfony\Component\Yaml\Yaml;

$autoloadPath1 = __DIR__ . '/../../../autoload.php';
$autoloadPath2 = __DIR__ . '/../vendor/autoload.php';
if (file_exists($autoloadPath1)) {
    require_once $autoloadPath1;
} else {
    require_once $autoloadPath2;
}

function getObjectFromFile($pathToFile, $format = '')
{
    if (!$format) {
        $format = pathinfo($pathToFile, PATHINFO_EXTENSION);
    }

    $content = getContent($pathToFile);

    $result = [];
    switch ($format) {
        case 'json' :
            $result = parseJson($content);
            break;
        case 'yaml' :
            $result = parseYaml($content);
            break;
    }

    return $result;
}

function parseJson($content)
{
    return json_decode($content);
}

function parseYaml($content)
{
    return Yaml::parse($content, Yaml::PARSE_OBJECT_FOR_MAP);
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
