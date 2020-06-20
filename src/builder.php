<?php

namespace App\Gendiff;

function builderTree($first, $second)
{
    $unique_keys = array_values(array_unique(array_merge(array_keys($first), array_keys($second))));

    return array_map(function ($child) use ($first, $second) {
        if (isset($first[$child]) && isset($second[$child]) && ($first[$child] !== $second[$child])) {
            return [
                'node'       => $child,
                'type'       => 'changed',
                'prevValue'  => $first[$child],
                'value'      => $second[$child]
            ];
        }

        if (isset($first[$child]) && isset($second[$child]) && ($first[$child] === $second[$child])) {
            return [
                'node'  => $child,
                'type'  => 'unchanged',
                'value' => $first[$child],
            ];
        }

        if (isset($first[$child]) && !isset($second[$child])) {
            return [
                'node'  => $child,
                'type'  => 'removed',
                'value' => $first[$child],
            ];
        }

        if (!isset($first[$child]) && isset($second[$child])) {
            return [
                'node'  => $child,
                'type'  => 'added',
                'value' => $second[$child],
            ];
        }
    }, $unique_keys);
}
