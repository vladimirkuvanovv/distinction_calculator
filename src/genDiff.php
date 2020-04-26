<?php

namespace Gendiff;

function getDifferenceFiles($pathToFileBefore, $pathToFileAfter)
{
    if (!$pathToFileBefore || !$pathToFileAfter) {
        return 'wrong paths to parameters';
    }

    if (!file_exists($pathToFileBefore) || !file_exists($pathToFileAfter)) {
        return 'files not exist';
    }

    $jsonFileBefore = file_get_contents($pathToFileBefore);
    $jsonFileAfter = file_get_contents($pathToFileAfter);


    $beforeDecode = json_decode($jsonFileBefore, true);
    $afterDecode = json_decode($jsonFileAfter, true);
    //найдем элементы, которых нет во втором массиве
    $elementsNotAtSecondFile = array_diff_key($beforeDecode, $afterDecode);
    //найдем элементы, которых нет в первом массиве
    $elementsNotAtFirstFile = array_diff_key($afterDecode, $beforeDecode);

    $result = [];
    $result[] = "}";

    foreach ($beforeDecode as $keyBefore => $item) {
        foreach ($afterDecode as $keyAfter => $element) {
            if ($keyBefore === $keyAfter && $item === $element) {
                $result[] = "  {$keyBefore}: {$element}";
            }

            if ($keyBefore === $keyAfter && $item !== $element) {
                $result[] = "- {$keyBefore}: {$item}";
                $result[] = "+ {$keyAfter}: {$element}";
            }
        }
    }

    if (!empty($elementsNotAtSecondFile)) {
        foreach ($elementsNotAtSecondFile as $key => $element) {
            $result[] = "- {$key}: {$element}";
        }
    }

    if (!empty($elementsNotAtFirstFile)) {
        foreach ($elementsNotAtFirstFile as $key => $element) {
            $result[] = "+ {$key}: {$element}";
        }
    }

    $result[] = '}';
    $result = implode(PHP_EOL, $result);

    return $result;

//    $result = array_reduce($beforeDecode, function ($acc, $el) {
//
//    }, []);
}
