<?php

namespace App\Gendiff;

function genDiff($pathToFileBefore, $pathToFileAfter)
{
    $beforeDecode = json_decode(getContent($pathToFileBefore), true);
    $afterDecode = json_decode(getContent($pathToFileAfter), true);

    return getResultOfDifference(compareArrays($beforeDecode, $afterDecode));
}

function getResultOfDifference(array $result)
{
    $resultForString = [];

    foreach ($result as $key => $item) {
        if (isset($item['value']) && count($item) < 2) {
            $resultForString[] = sprintf('   %s: %s', $key, toString($item['value']));
        } elseif (!isset($item['value']) && isset($item['diff'])) {
            $resultForString[] = sprintf('-  %s: %s', $key, toString($item['diff']));
        } elseif (isset($item['value']) && isset($item['diff'])) {
            $resultForString[] = sprintf('+  %s: %s', $key, toString($item['value']));
            $resultForString[] = sprintf('-  %s: %s', $key, toString($item['diff']));
        } elseif (isset($item['value'])) {
            $resultForString[] = sprintf('+   %s: %s', $key, toString($item['value']));
        }
    }

    array_unshift($resultForString, '{');
    array_push($resultForString, '}');

    return implode(PHP_EOL, $resultForString);
}

function compareArrays($first, $second)
{
    //найдем элементы, которых нет во втором массиве
    $elementsNotAtSecond = array_diff_key($first, $second);
    //найдем элементы, которых нет в первом массиве
    $elementsNotAtFirst = array_diff_key($second, $first);

    $result = [];

    foreach ($first as $keyFirst => $item) {
        foreach ($second as $keySecond => $element) {
            if ($keyFirst === $keySecond) {
                $result[$keyFirst]['value'] = $element;

                if ($item !== $element) {
                    $result[$keyFirst]['diff'] = $item;
                }
            }
        }
    }

    if (!empty($elementsNotAtSecond)) {
        foreach ($elementsNotAtSecond as $key => $element) {
            $result[$key]['diff'] = $element;
            $result[$key]['value'] = null;
        }
    }

    if (!empty($elementsNotAtFirst)) {
        foreach ($elementsNotAtFirst as $key => $element) {
            $result[$key]['value'] = $element;
            $result[$key]['diff'] = null;
        }
    }

    return $result;
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

function toString($value)
{
    if (is_bool($value)) {
        return $value ? 'true' : 'false';
    }

    return $value;
}
