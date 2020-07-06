<?php

namespace Gendiff\Builder;

function builderTree($dataBefore, $dataAfter)
{
    $unique_keys = array_values(
        array_unique(array_merge(array_keys($dataBefore), array_keys($dataAfter)))
    );

    return array_map(function ($key) use ($dataBefore, $dataAfter) {
        if (
            isset($dataBefore[$key], $dataAfter[$key])
            && is_array($dataBefore[$key])
            && is_array($dataAfter[$key])
        ) {
            return [
                'key'      => $key,
                'type'     => 'nested',
                'children' => builderTree($dataBefore[$key], $dataAfter[$key])
            ];
        }

        if (
            isset($dataBefore[$key], $dataAfter[$key])
            && ($dataBefore[$key] !== $dataAfter[$key])
        ) {
            return [
                'key'           => $key,
                'type'          => 'changed',
                'previousValue' => $dataBefore[$key],
                'currentValue'  => $dataAfter[$key]
            ];
        }

        if (
            isset($dataBefore[$key], $dataAfter[$key])
            && ($dataBefore[$key] === $dataAfter[$key])
        ) {
            return [
                'key'          => $key,
                'type'         => 'unchanged',
                'currentValue' => $dataBefore[$key],
            ];
        }

        if (isset($dataBefore[$key]) && !isset($dataAfter[$key])) {
            return [
                'key'           => $key,
                'type'          => 'removed',
                'previousValue' => $dataBefore[$key],
            ];
        }

        if (!isset($dataBefore[$key]) && isset($dataAfter[$key])) {
            return [
                'key'          => $key,
                'type'         => 'added',
                'currentValue' => $dataAfter[$key],
            ];
        }

        return [];
    }, $unique_keys);
}
