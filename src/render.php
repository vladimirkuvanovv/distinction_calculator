<?php

namespace App\Gendiff;

function getResultOfDifference(array $result)
{
    $resultForString = [];

    foreach ($result as $item) {
        if (is_array($item)) {
            switch ($item['type']) {
                case 'unchanged':
                    $resultForString[] = sprintf('   %s: %s', $item['node'], toString($item['value']));
                    break;
                case 'changed':
                    $resultForString[] = sprintf('+  %s: %s', $item['node'], toString($item['value']));
                    $resultForString[] = sprintf('-  %s: %s', $item['node'], toString($item['prevValue']));
                    break;
                case 'removed':
                    $resultForString[] = sprintf('-  %s: %s', $item['node'], toString($item['value']));
                    break;
                case 'added':
                    $resultForString[] = sprintf('+  %s: %s', $item['node'], toString($item['value']));
                    break;
            }
        }
    }

    array_unshift($resultForString, '{');
    array_push($resultForString, '}');

    return implode(PHP_EOL, $resultForString);
}

function toString($value)
{
    if (is_bool($value)) {
        return $value ? 'true' : 'false';
    }

    return $value;
}
