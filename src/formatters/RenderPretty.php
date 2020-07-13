<?php

namespace Gendiff\Formatters\RenderPretty;

function renderPretty(array $tree)
{
    return buildPretty($tree);
}

function buildPretty($tree, $level = 0)
{
    $spaces = '    ';
    $offset = str_repeat($spaces, $level);

    $nodesForPretty = array_map(function ($node) use ($spaces, $offset, $level) {
        $node_name = $node['key'];

        switch ($node['type']) {
            case 'nested':
                $newChildren = buildPretty($node['children'], $level + 1);
                return $spaces . "$node_name: " . $newChildren;
            case 'unchanged':
                return $spaces . $offset . "$node_name: " . stringify($node['currentValue'], $level + 1);
                break;
            case 'changed':
                return $offset
                    . "  + $node_name: "
                    . stringify($node['currentValue'], $level + 1, $spaces)
                    . PHP_EOL
                    . $offset
                    . "  - $node_name: "
                    . stringify($node['previousValue'], $level + 1, $spaces);
                break;
            case 'removed':
                return $offset
                    . "  - $node_name: "
                    . stringify($node['previousValue'], $level + 1, $spaces);
                break;
            case 'added':
                return $offset
                    . "  + $node_name: "
                    . stringify($node['currentValue'], $level + 1, $spaces);
                break;
        }
    }, $tree);

    array_push($nodesForPretty, $offset . "}");

    return "{" . PHP_EOL . implode(PHP_EOL, array_filter($nodesForPretty));
}

function stringify($value, $level = 0, $spaces = '')
{
    if (is_bool($value)) {
        return $value ? 'true' : 'false';
    }

    if (is_array($value)) {
        $offset = str_repeat($spaces, $level);

        $keys = array_keys($value);

        $nestedItem = array_map(function ($key) use ($spaces, $offset, $value) {
            return $spaces . $offset . "$key: " . $value[$key];
        }, $keys);

        array_push($nestedItem, $offset . '}');

        return "{" . PHP_EOL . implode(PHP_EOL, $nestedItem);
    }

    return $value;
}
