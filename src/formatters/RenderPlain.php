<?php

namespace Gendiff\Formatters\RenderPlain;

function renderPlain(array $tree)
{
    return buildPlain($tree);
}

function buildPlain($tree, $parent = '')
{
    $nodesForPlain = array_map(function ($node) use ($parent) {
        $key = $node['key'];

        switch ($node['type']) {
            case 'nested':
                return buildPlain($node['children'], "{$parent}{$key}.");
            case 'changed':
                return sprintf(
                    "Property '{$parent}{$key}' was changed. From '%s' to '%s'",
                    stringify($node['previousValue']),
                    stringify($node['currentValue'])
                );
                break;
            case 'removed':
                return sprintf("Property '{$parent}{$key}' was removed");
                break;
            case 'added':
                return sprintf(
                    "Property '{$parent}{$key}' was added with value: '%s'",
                    stringify($node['currentValue'])
                );
                break;
        }
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
