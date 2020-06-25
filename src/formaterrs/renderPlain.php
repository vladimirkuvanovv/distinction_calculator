<?php

namespace App\Gendiff\Formatter;

function renderPlain(array $tree)
{
    /*$resultForPlain = array_map(function ($item) {
        if ($item['type']) {
            switch ($item['type']) {
                case 'nested':
                    $newChildren = renderPlain($item['children']);
//                    return $item['key'] . ':' . $newChildren;
                    break;
                case 'changed':
                    $resultPlain[] = sprintf(
                        "Property '%s' was changed. From '%s' to '%s'",
                        $item['key'],
                        toString($item['currentValue']),
                        toString($item['previousValue'])
                    );
                    break;
                case 'removed':
                    $resultPlain[] = sprintf("Property '%s' was removed", $item['key']);
                    break;
                case 'added':
                    $resultPlain[] = sprintf(
                        "Property '%s' was added with value: '%s'",
                        $item['key'],
                        toString($item['currentValue'])
                    );
                    break;
            }

            return implode(PHP_EOL, $resultPlain);
        }
    }, $tree);

    $resultForPlain = array_filter($resultForPlain, function ($property) {
        return !is_null($property);
    });

    return implode(PHP_EOL, $resultForPlain);*/

    return buildPlain($tree, '');

}

function buildPlain($tree, $acc)
{
    $resultForPlain = array_map(function ($item) use ($acc) {
        $acc = sprintf(
            !empty($acc) ? '%s.%s' : '%s',
            !empty($acc) ? $acc : $item['key'],
            !empty($acc) ? $item['key'] : ''
        );

        if ($item['type']) {
            switch ($item['type']) {
                case 'nested':
                    return buildPlain($item['children'], $acc);
                    break;
                case 'changed':
                    $resultPlain[] = sprintf(
                        "Property '%s' was changed. From '%s' to '%s'",
                        $acc,
                        toString($item['currentValue']),
                        toString($item['previousValue'])
                    );
                    break;
                case 'removed':
                    $resultPlain[] = sprintf("Property '%s' was removed", $acc);
                    break;
                case 'added':
                    $resultPlain[] = sprintf(
                        "Property '%s' was added with value: '%s'",
                        $acc,
                        toString($item['currentValue'])
                    );
                    break;
            }

            if (isset($resultPlain)) {
                return implode(PHP_EOL, $resultPlain);
            }
        }
    }, $tree);

    $resultForPlain = array_filter($resultForPlain, function ($property) {
        return !is_null($property);
    });

    return implode(PHP_EOL, $resultForPlain);
}
