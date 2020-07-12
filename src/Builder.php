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
            return buildNode($key, 'nested', [], [], builderTree($dataBefore[$key], $dataAfter[$key]));
        }

        if (!isset($dataBefore[$key]) && isset($dataAfter[$key])) {
            return buildNode($key, 'added', [], $dataAfter[$key]);
        }

        if (isset($dataBefore[$key]) && !isset($dataAfter[$key])) {
            return buildNode($key, 'removed', $dataBefore[$key]);
        }

        if ($dataBefore[$key] === $dataAfter[$key]) {
            return buildNode($key, 'unchanged', [], $dataAfter[$key]);
        }

        if ($dataBefore[$key] !== $dataAfter[$key]) {
            return buildNode($key, 'changed', $dataBefore[$key], $dataAfter[$key]);
        }

        return [];
    }, $unique_keys);
}

function buildNode($key, $type, $dataBefore = [], $dataAfter = [], $children = [])
{
    return [
        'key'           => $key,
        'type'          => $type,
        'previousValue' => $dataBefore,
        'currentValue'  => $dataAfter,
        'children'      => $children
    ];
}
