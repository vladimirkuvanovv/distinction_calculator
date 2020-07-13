<?php

namespace Gendiff\Formatters\RenderPretty;

function renderPretty(array $tree)
{
    return buildPretty($tree);
}

function buildPretty($tree, $level = 0)
{
    $offset = getOffset($level);

    $nodesForPretty = array_map(function ($node) use ($level, $offset) {
        $node_name = $node['key'];

        switch ($node['type']) {
            case 'nested':
                $newChildren = buildPretty($node['children'], $level + 1);
                return "    $node_name: " . $newChildren;
            case 'unchanged':
                return $offset . "    $node_name: " . stringify($node['currentValue'], $level);
                break;
            case 'changed':
                return $offset
                    . "  + $node_name: "
                    . stringify($node['currentValue'], $level + 1)
                    . PHP_EOL
                    . $offset
                    . "  - $node_name: "
                    . stringify($node['previousValue'], $level + 1);
                break;
            case 'removed':
                return $offset
                    . "  - $node_name: "
                    . stringify($node['previousValue'], $level + 1);
                break;
            case 'added':
                return $offset
                    . "  + $node_name: "
                    . stringify($node['currentValue'], $level + 1);
                break;
        }

    }, $tree);

    array_push($nodesForPretty, $offset . "}");

    return "{" . PHP_EOL . implode(PHP_EOL, array_filter($nodesForPretty));
}

function stringify($value, $level = 0)
{
    if (is_bool($value)) {
        return $value ? 'true' : 'false';
    }

    if (is_array($value)) {
        $offset = getOffset($level);

        $keys = array_keys($value);

        $nestedItem = array_map(function ($key) use ($offset, $value) {
            return $offset . "$key: " . $value[$key];
        }, $keys);

        array_push($nestedItem, getOffset($level) . '}');

        return "{" . PHP_EOL . implode(PHP_EOL, $nestedItem);
    }

    return $value;
}

function getOffset($level)
{
    $spaces = '    ';
    return str_repeat($spaces, $level);
}
