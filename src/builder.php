<?php

namespace Gendiff;

function builderTree($beforeDiffProperties, $afterDiffProperties)
{
    $unique_keys = array_values(
        array_unique(array_merge(array_keys($beforeDiffProperties), array_keys($afterDiffProperties)))
    );

    return array_map(function ($key) use ($beforeDiffProperties, $afterDiffProperties) {
        if (
            isset($beforeDiffProperties[$key], $afterDiffProperties[$key])
            && is_array($beforeDiffProperties[$key])
            && is_array($afterDiffProperties[$key])
        ) {
            return [
                'key'      => $key,
                'type'     => 'nested',
                'children' => builderTree($beforeDiffProperties[$key], $afterDiffProperties[$key])
            ];
        }

        if (
            isset($beforeDiffProperties[$key], $afterDiffProperties[$key])
            && ($beforeDiffProperties[$key] !== $afterDiffProperties[$key])
        ) {
            return [
                'key'           => $key,
                'type'          => 'changed',
                'previousValue' => $beforeDiffProperties[$key],
                'currentValue'  => $afterDiffProperties[$key]
            ];
        }

        if (
            isset($beforeDiffProperties[$key], $afterDiffProperties[$key])
            && ($beforeDiffProperties[$key] === $afterDiffProperties[$key])
        ) {
            return [
                'key'          => $key,
                'type'         => 'unchanged',
                'currentValue' => $beforeDiffProperties[$key],
            ];
        }

        if (isset($beforeDiffProperties[$key]) && !isset($afterDiffProperties[$key])) {
            return [
                'key'           => $key,
                'type'          => 'removed',
                'previousValue' => $beforeDiffProperties[$key],
            ];
        }

        if (!isset($beforeDiffProperties[$key]) && isset($afterDiffProperties[$key])) {
            return [
                'key'          => $key,
                'type'         => 'added',
                'currentValue' => $afterDiffProperties[$key],
            ];
        }

        return [];
    }, $unique_keys);
}
