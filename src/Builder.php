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
            return getValueForNode($key, 'nested', $dataBefore[$key], $dataAfter[$key]);
        }

        if (!isset($dataBefore[$key]) && isset($dataAfter[$key])) {
            return getValueForNode($key, 'added', [], $dataAfter[$key]);
        }

        if (isset($dataBefore[$key]) && !isset($dataAfter[$key])) {
            return getValueForNode($key, 'removed', $dataBefore[$key]);
        }

        if (
            isset($dataBefore[$key], $dataAfter[$key])
            && ($dataBefore[$key] === $dataAfter[$key])
        ) {
            return getValueForNode($key, 'unchanged', $dataBefore[$key]);
        }

        if (
            isset($dataBefore[$key], $dataAfter[$key])
            && ($dataBefore[$key] !== $dataAfter[$key])
        ) {
            return getValueForNode($key, 'changed', $dataBefore[$key], $dataAfter[$key]);
        }

        return [];
    }, $unique_keys);
}

function getValueForNode($key, $type, $dataBefore = [], $dataAfter = [])
{
    switch ($type) {
        case 'nested':
            return [
                'key'      => $key,
                'type'     => $type,
                'children' => builderTree($dataBefore, $dataAfter)
            ];
        case 'added':
            return [
                'key'          => $key,
                'type'         => $type,
                'currentValue' => $dataAfter,
            ];
        case 'removed':
            return [
                'key'           => $key,
                'type'          => $type,
                'previousValue' => $dataBefore,
            ];
        case 'changed':
            return [
                'key'           => $key,
                'type'          => $type,
                'previousValue' => $dataBefore,
                'currentValue'  => $dataAfter
            ];
        case 'unchanged':
            return [
                'key'          => $key,
                'type'         => $type,
                'currentValue' => $dataBefore,
            ];
    }
}
