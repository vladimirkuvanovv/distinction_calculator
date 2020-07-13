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
        [$nodeName, $type] = [$node['key'], $node['type']];

        switch ($type) {
            case 'nested':
                $newChildren = buildPretty($node['children'], $level + 1);
                return $spaces . "$nodeName: " . $newChildren;
            case 'unchanged':
                return $spaces . $offset . "$nodeName: " . stringify($node['dataAfter'], $offset, $level + 1);
            case 'changed':
                return $offset
                    . "  + $nodeName: "
                    . stringify($node['dataAfter'], $offset, $level + 1)
                    . PHP_EOL
                    . $offset
                    . "  - $nodeName: "
                    . stringify($node['dataBefore'], $offset, $level + 1);
            case 'removed':
                return $offset
                    . "  - $nodeName: "
                    . stringify($node['dataBefore'], $offset, $level + 1);
            case 'added':
                return $offset
                    . "  + $nodeName: "
                    . stringify($node['dataAfter'], $offset, $level + 1);
        }
    }, $tree);

    array_push($nodesForPretty, $offset . "}");

    return "{" . PHP_EOL . implode(PHP_EOL, array_filter($nodesForPretty));
}

function stringify($value, $parentOffset, $level = 0)
{
    if (is_bool($value)) {
        return $value ? 'true' : 'false';
    }

    if (is_array($value)) {
        $spaces = '    ';
        $offset = str_repeat($spaces, $level);

        $keys = array_keys($value);

        $nestedItem = array_map(function ($key) use ($parentOffset, $offset, $value) {
            return $parentOffset . $offset . "$key: " . $value[$key];
        }, $keys);

        array_push($nestedItem, $offset . '}');

        return "{" . PHP_EOL . implode(PHP_EOL, $nestedItem);
    }

    return $value;
}
