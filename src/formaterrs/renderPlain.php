<?php

namespace App\Gendiff\Formatter;

function renderPlain(array $tree)
{
    return buildPlain($tree, '');
}

function buildPlain($tree, $acc)
{
    $resultForPlain = array_map(function ($property) use ($acc) {
        $acc = sprintf(
            !empty($acc) ? '%s.%s' : '%s',
            !empty($acc) ? $acc : $property['key'],
            !empty($acc) ? $property['key'] : ''
        );

        if ($property['type']) {
            switch ($property['type']) {
                case 'nested':
                    return buildPlain($property['children'], $acc);
                    break;
                case 'changed':
                    $resultPlain[] = sprintf(
                        "Property '%s' was changed. From '%s' to '%s'",
                        $acc,
                        toString($property['currentValue']),
                        toString($property['previousValue'])
                    );
                    break;
                case 'removed':
                    $resultPlain[] = sprintf("Property '%s' was removed", $acc);
                    break;
                case 'added':
                    $resultPlain[] = sprintf(
                        "Property '%s' was added with value: '%s'",
                        $acc,
                        toString($property['currentValue'])
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
