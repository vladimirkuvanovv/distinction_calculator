<?php

namespace App\Gendiff;

function builderTree($first, $second)
{
    $unique_keys = getUniqueKeys($first, $second);

    return array_map(function ($child) use ($first, $second) {
        if (isset($first[$child], $second[$child]) && is_array($first[$child]) && is_array($second[$child])) {
            return [
                'node'     => $child,
                'type'     => 'unchanged',
                'children' => builderTree($first[$child], $second[$child])
            ];
        }

        if (isset($first[$child], $second[$child]) && ($first[$child] !== $second[$child])) {
            return [
                'node'      => $child,
                'type'      => 'changed',
                'prevValue' => $first[$child],
                'value'     => $second[$child]
            ];
        }

        if (isset($first[$child], $second[$child]) && ($first[$child] === $second[$child])) {
            return [
                'node'   => $child,
                'type'   => 'unchanged',
                'value'  => $first[$child],
            ];
        }

        if (isset($first[$child]) && !isset($second[$child])) {
            return [
                'node'   => $child,
                'type'   => 'removed',
                'value'  => $first[$child],
            ];
        }

        if (!isset($first[$child]) && isset($second[$child])) {
            return [
                'node'   => $child,
                'type'   => 'added',
                'value'  => $second[$child],
            ];
        }
    }, $unique_keys);
}

function getUniqueKeys($first, $second)
{
    return array_values(
        array_unique(
            array_merge(
                isset($first) ? array_keys($first) : [],
                isset($second) ? array_keys($second) : []
            )
        )
    );
}
