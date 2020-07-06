<?php

namespace Gendiff\Formatters\RenderPlain;

function renderPlain(array $tree)
{
    return buildPlain($tree);
}

function buildPlain($tree, $fullNameProperty = '')
{
    $resultForPlain = array_map(function ($node) use ($fullNameProperty) {
        $key = $node['key'];
        $fullNameProperty .= $key;
        if ($node['type']) {
            switch ($node['type']) {
                case 'nested':
                    return buildPlain($node['children'], "$fullNameProperty.");
                    break;
                case 'changed':
                    $itemForPlain[] = sprintf(
                        "Property '{$fullNameProperty}' was changed. From '%s' to '%s'",
                        stringifyForRenderPlain($node['previousValue']),
                        stringifyForRenderPlain($node['currentValue'])
                    );
                    break;
                case 'removed':
                    $itemForPlain[] = sprintf("Property '{$fullNameProperty}' was removed");
                    break;
                case 'added':
                    $itemForPlain[] = sprintf(
                        "Property '{$fullNameProperty}' was added with value: '%s'",
                        stringifyForRenderPlain($node['currentValue'])
                    );
                    break;
            }

            if (isset($itemForPlain)) {
                return implode(PHP_EOL, $itemForPlain);
            }
        }

        return null;
    }, $tree);

    return implode(PHP_EOL, array_filter($resultForPlain, function ($item) {
        return !empty($item);
    }));
}

function stringifyForRenderPlain($value)
{
    if (is_bool($value)) {
        return $value ? 'true' : 'false';
    }

    if (is_array($value)) {
        return 'complex value';
    }

    return $value;
}
