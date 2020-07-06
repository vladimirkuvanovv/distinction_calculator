<?php

namespace Gendiff\Formatters\RenderPlain;

function renderPlain(array $tree)
{
    return buildPlain($tree);
}

function buildPlain($tree, $fullNameNode = '')
{
    $nodesForPlain = array_map(function ($node) use ($fullNameNode) {
        $key = $node['key'];
        $fullNameNode .= $key;
        if ($node['type']) {
            switch ($node['type']) {
                case 'nested':
                    return buildPlain($node['children'], "$fullNameNode.");
                    break;
                case 'changed':
                    $itemForPlain[] = sprintf(
                        "Property '{$fullNameNode}' was changed. From '%s' to '%s'",
                        stringify($node['previousValue']),
                        stringify($node['currentValue'])
                    );
                    break;
                case 'removed':
                    $itemForPlain[] = sprintf("Property '{$fullNameNode}' was removed");
                    break;
                case 'added':
                    $itemForPlain[] = sprintf(
                        "Property '{$fullNameNode}' was added with value: '%s'",
                        stringify($node['currentValue'])
                    );
                    break;
            }

            if (isset($itemForPlain)) {
                return implode(PHP_EOL, $itemForPlain);
            }
        }

        return null;
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
