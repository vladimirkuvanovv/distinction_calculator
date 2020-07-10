<?php

namespace Gendiff\Formatters\RenderPlain;

function renderPlain(array $tree)
{
    return buildPlain($tree);
}

function buildPlain($tree, $keys = [])
{
    $nodesForPlain = array_map(function ($node) use ($keys) {
        $keys[] = $node['key'];
        $itemForPlain = [];

        switch ($node['type']) {
            case 'nested':
                return buildPlain($node['children'], $keys);
            case 'changed':
                $itemForPlain[] = sprintf(
                    "Property '%s' was changed. From '%s' to '%s'",
                    getFullName($keys),
                    stringify($node['previousValue']),
                    stringify($node['currentValue'])
                );
                break;
            case 'removed':
                $itemForPlain[] = sprintf("Property '%s' was removed", getFullName($keys));
                break;
            case 'added':
                $itemForPlain[] = sprintf(
                    "Property '%s' was added with value: '%s'",
                    getFullName($keys),
                    stringify($node['currentValue'])
                );
                break;
        }

        return implode(PHP_EOL, $itemForPlain);

    }, $tree);

    return implode(PHP_EOL, array_filter($nodesForPlain, function ($item) {
        return !empty($item);
    }));
}

function stringify($value)
{
    if (is_bool($value)) {
        return $value ? 'true' : 'false';
    }

    if (is_array($value)) {
        return 'complex value';
    }

    return $value;
}

function getFullName($keys)
{
    return implode('.', $keys);
}
