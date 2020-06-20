<?php

namespace App\Gendiff;

function genDiff($pathToFileBefore, $pathToFileAfter, $format = '')
{
    $beforeDecode = getObjectFromFile($pathToFileBefore, $format);
    $afterDecode  = getObjectFromFile($pathToFileAfter, $format);

    return getResultOfDifference(builderTree($beforeDecode, $afterDecode));
}

function getObjectFromFile($pathToFile, $format = '')
{
    if (!$format) {
        $format = pathinfo($pathToFile, PATHINFO_EXTENSION);
    }

    $content = getContent($pathToFile);

    $result = [];
    switch ($format) {
        case 'json':
            $result = parseJson($content);
            break;
        case 'yaml':
            $result = parseYaml($content);
            break;
    }

    return $result;
}
