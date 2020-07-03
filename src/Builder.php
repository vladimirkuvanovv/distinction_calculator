<?php

namespace Gendiff\Builder;

function builderTree($beforeProperties, $afterProperties)
{
    $unique_keys = array_values(
        array_unique(array_merge(array_keys($beforeProperties), array_keys($afterProperties)))
    );

    return array_map(function ($key) use ($beforeProperties, $afterProperties) {
        if (
            isset($beforeProperties[$key], $afterProperties[$key])
            && is_array($beforeProperties[$key])
            && is_array($afterProperties[$key])
        ) {
            return [
                'key'      => $key,
                'type'     => 'nested',
                'children' => builderTree($beforeProperties[$key], $afterProperties[$key])
            ];
        }

        if (
            isset($beforeProperties[$key], $afterProperties[$key])
            && ($beforeProperties[$key] !== $afterProperties[$key])
        ) {
            return [
                'key'           => $key,
                'type'          => 'changed',
                'previousValue' => $beforeProperties[$key],
                'currentValue'  => $afterProperties[$key]
            ];
        }

        if (
            isset($beforeProperties[$key], $afterProperties[$key])
            && ($beforeProperties[$key] === $afterProperties[$key])
        ) {
            return [
                'key'          => $key,
                'type'         => 'unchanged',
                'currentValue' => $beforeProperties[$key],
            ];
        }

        if (isset($beforeProperties[$key]) && !isset($afterProperties[$key])) {
            return [
                'key'           => $key,
                'type'          => 'removed',
                'previousValue' => $beforeProperties[$key],
            ];
        }

        if (!isset($beforeProperties[$key]) && isset($afterProperties[$key])) {
            return [
                'key'          => $key,
                'type'         => 'added',
                'currentValue' => $afterProperties[$key],
            ];
        }

        return [];
    }, $unique_keys);
}
