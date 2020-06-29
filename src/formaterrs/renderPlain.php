<?php

namespace App\Gendiff\Formatter;

function renderPlain(array $tree)
{
    return buildPlain($tree);
}

function buildPlain($tree, $acc = [])
{
    $resultForPlain = [];

    foreach ($tree as $property) {
        $value = getValueForNode($property, $acc);
        if ($value) {
            $resultForPlain[] = $value;
        }
    }

    return implode(PHP_EOL, array_filter($resultForPlain));
}

function getValueForNode($property, $acc)
{
    $key = $property['key'];
    $acc[] = $key;

    if ($property['type']) {
        switch ($property['type']) {
            case 'nested':
                return buildPlain($property['children'], $acc);
                break;
            case 'changed':
                $resultPlain[] = sprintf(
                    "Property '%s' was changed. From '%s' to '%s'",
                    getFullPropertyName($acc),
                    toStringForRenderPlain($property['currentValue']),
                    toStringForRenderPlain($property['previousValue'])
                );
                break;
            case 'removed':
                $resultPlain[] = sprintf("Property '%s' was removed", getFullPropertyName($acc));
                break;
            case 'added':
                $resultPlain[] = sprintf(
                    "Property '%s' was added with value: '%s'",
                    getFullPropertyName($acc),
                    toStringForRenderPlain($property['currentValue'])
                );
                break;
        }

        if (isset($resultPlain)) {
            return implode(PHP_EOL, $resultPlain);
        }
    }
}

function getFullPropertyName($acc)
{
    return implode('.', $acc);
}

function toStringForRenderPlain($value)
{
    if (is_bool($value)) {
        return $value ? 'true' : 'false';
    }

    if (is_array($value)) {
        return 'complex value';
    }

    return $value;
}
