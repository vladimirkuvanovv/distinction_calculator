<?php

namespace Gendiff\Formatters\RenderPlain;

function renderPlain(array $properties)
{
    return buildPlain($properties);
}

function buildPlain($properties, $fullNameProperty = '')
{
    $resultForPlain = array_map(function ($property) use ($fullNameProperty) {
        $key = $property['key'];
        $fullNameProperty .= $key;
        if ($property['type']) {
            switch ($property['type']) {
                case 'nested':
                    return buildPlain($property['children'], "$fullNameProperty.");
                    break;
                case 'changed':
                    $resultPlain[] = sprintf(
                        "Property '{$fullNameProperty}' was changed. From '%s' to '%s'",
                        toStringForRenderPlain($property['previousValue']),
                        toStringForRenderPlain($property['currentValue'])
                    );
                    break;
                case 'removed':
                    $resultPlain[] = sprintf("Property '{$fullNameProperty}' was removed");
                    break;
                case 'added':
                    $resultPlain[] = sprintf(
                        "Property '{$fullNameProperty}' was added with value: '%s'",
                        toStringForRenderPlain($property['currentValue'])
                    );
                    break;
            }

            if (isset($resultPlain)) {
                return implode(PHP_EOL, $resultPlain);
            }
        }

        return null;
    }, $properties);

    return implode(PHP_EOL, array_filter($resultForPlain, function ($item) {
        return !empty($item);
    }));
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
