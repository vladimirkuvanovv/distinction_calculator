<?php

namespace App\Gendiff\Formatters;

function renderPretty(array $tree)
{
    return buildPretty($tree);
}

function buildPretty($tree, $level = 0)
{
    $offset = getOffset($level);

    $resultForString = array_map(function ($property) use ($level, $offset) {
        $property_name = $property['key'];
        $resultString = [];

        if ($property['type']) {
            switch ($property['type']) {
                case 'nested':
                    $level += 1;
                    $offset = getOffset($level);
                    $newChildren = buildPretty($property['children'], $level);
                    return $offset . "$property_name: " . $newChildren;
                    break;
                case 'unchanged':
                    $level += 2;
                    $resultString[] = $offset
                        . "    $property_name: "
                        . toStringForRenderPretty($property['currentValue'], $level);
                    break;
                case 'changed':
                    $level += 2;
                    $resultString[] = $offset
                        . "  + $property_name: "
                        . toStringForRenderPretty($property['currentValue'], $level);
                    $resultString[] = $offset
                        . "  - $property_name: "
                        . toStringForRenderPretty($property['previousValue'], $level);
                    break;
                case 'removed':
                    $level += 2;
                    $resultString[] = $offset
                        . "  - $property_name: "
                        . toStringForRenderPretty($property['previousValue'], $level);
                    break;
                case 'added':
                    $level += 2;
                    $resultString[] = $offset
                        . "  + $property_name: "
                        . toStringForRenderPretty($property['currentValue'], $level);
                    break;
            }

            return implode(PHP_EOL, $resultString);
        }

        return null;
    }, $tree);

    array_unshift($resultForString, '{');
    array_push($resultForString, $offset . "}");

    return implode(PHP_EOL, array_filter($resultForString));
}

function toStringForRenderPretty($value, $level = 0)
{
    $offset = 0;
    $nestedString = [];

    if (is_bool($value)) {
        return $value ? 'true' : 'false';
    }

    if (is_array($value)) {
        if ($level) {
            $offset = getOffset($level);
        }

        $keys = array_keys($value);

        $nestedString = array_map(function ($key) use ($offset, $value) {
            return $offset . "$key: " . $value[$key];
        }, $keys);

        array_unshift($nestedString, '{');
        array_push($nestedString, getOffset($level - 1) . '}');

        return implode(PHP_EOL, $nestedString);
    }

    return $value;
}

function getOffset($level)
{
    $spaces = '    ';
    return str_repeat($spaces, $level);
}
