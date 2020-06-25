<?php

namespace App\Gendiff;

function builderTree($first, $second)
{
    $unique_keys = getUniqueKeys($first, $second);

    return array_map(function ($key) use ($first, $second) {
        if (isset($first[$key], $second[$key]) && is_array($first[$key]) && is_array($second[$key])) {
            return [
                'key'      => $key,
                'type'     => 'nested',
                'children' => builderTree($first[$key], $second[$key])
            ];
        }

        if (isset($first[$key], $second[$key]) && ($first[$key] !== $second[$key])) {
            return [
                'key'           => $key,
                'type'          => 'changed',
                'previousValue' => $first[$key],
                'currentValue'  => $second[$key]
            ];
        }

        if (isset($first[$key], $second[$key]) && ($first[$key] === $second[$key])) {
            return [
                'key'          => $key,
                'type'         => 'unchanged',
                'currentValue' => $first[$key],
            ];
        }

        if (isset($first[$key]) && !isset($second[$key])) {
            return [
                'key'           => $key,
                'type'          => 'removed',
                'previousValue' => $first[$key],
            ];
        }

        if (!isset($first[$key]) && isset($second[$key])) {
            return [
                'key'          => $key,
                'type'         => 'added',
                'currentValue' => $second[$key],
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
