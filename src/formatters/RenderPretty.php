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
        $itemForPretty = [];

        if ($node['type']) {
            switch ($node['type']) {
                case 'nested':
                    $level += 1;
                    $offset = getOffset($level);
                    $newChildren = buildPretty($node['children'], $level);
                    return $offset . "$node_name: " . $newChildren;
                case 'unchanged':
                    $level += 2;
                    $itemForPretty[] = $offset
                        . "    $node_name: "
                        . stringifyForRenderPretty($node['currentValue'], $level);
                    break;
                case 'changed':
                    $level += 2;
                    $itemForPretty[] = $offset
                        . "  + $node_name: "
                        . stringifyForRenderPretty($node['currentValue'], $level);
                    $itemForPretty[] = $offset
                        . "  - $node_name: "
                        . stringifyForRenderPretty($node['previousValue'], $level);
                    break;
                case 'removed':
                    $level += 2;
                    $itemForPretty[] = $offset
                        . "  - $node_name: "
                        . stringifyForRenderPretty($node['previousValue'], $level);
                    break;
                case 'added':
                    $level += 2;
                    $itemForPretty[] = $offset
                        . "  + $node_name: "
                        . stringifyForRenderPretty($node['currentValue'], $level);
                    break;
            }

            return implode(PHP_EOL, $itemForPretty);
        }

        return null;
    }, $tree);

    array_unshift($nodesForPretty, '{');
    array_push($nodesForPretty, $offset . "}");

    return implode(PHP_EOL, array_filter($nodesForPretty));
}

function stringifyForRenderPretty($value, $level = 0)
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

        array_unshift($nestedItem, '{');
        array_push($nestedItem, getOffset($level - 1) . '}');

        return implode(PHP_EOL, $nestedItem);
    }

    return $value;
}

function getOffset($level)
{
    $spaces = '    ';
    return str_repeat($spaces, $level);
}
