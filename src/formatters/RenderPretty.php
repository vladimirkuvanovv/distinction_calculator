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
        $level += 1;
        $itemForPretty = [];

        switch ($node['type']) {
            case 'nested':
                $offset = getOffset($level);
                $newChildren = buildPretty($node['children'], $level);
                return $offset . "$node_name: " . $newChildren;
            case 'unchanged':
                $itemForPretty[] = $offset
                    . "    $node_name: "
                    . stringify($node['currentValue'], $level);
                break;
            case 'changed':
                $itemForPretty[] = $offset
                    . "  + $node_name: "
                    . stringify($node['currentValue'], $level);
                $itemForPretty[] = $offset
                    . "  - $node_name: "
                    . stringify($node['previousValue'], $level);
                break;
            case 'removed':
                $itemForPretty[] = $offset
                    . "  - $node_name: "
                    . stringify($node['previousValue'], $level);
                break;
            case 'added':
                $itemForPretty[] = $offset
                    . "  + $node_name: "
                    . stringify($node['currentValue'], $level);
                break;
        }

        return implode(PHP_EOL, $itemForPretty);

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
        $level += 1;
        $offset = getOffset($level);

        $keys = array_keys($value);

        $nestedItem = array_map(function ($key) use ($offset, $value) {
            return $offset . "$key: " . $value[$key];
        }, $keys);

        array_push($nestedItem, getOffset($level - 1) . '}');

        return "{" . PHP_EOL . implode(PHP_EOL, $nestedItem);
    }

    return $value;
}

function getOffset($level)
{
    $spaces = '    ';
    return str_repeat($spaces, $level);
}
