<?php

namespace App\Gendiff\Formatter;

use function App\Gendiff\Helper\toString;

function renderPretty(array $tree)
{
    $resultForString = array_map(function ($item) {
        if ($item['type']) {
            switch ($item['type']) {
                case 'nested':
                    $newChildren = renderPretty($item['children']);
                    return '   ' . $item['key'] . ':' . $newChildren;
                    break;
                case 'unchanged':
                    $resultString[] = sprintf('   %s: %s', $item['key'], toString($item['currentValue']));
                    break;
                case 'changed':
                    $resultString[] = sprintf('+  %s: %s', $item['key'], toString($item['currentValue']));
                    $resultString[] = sprintf('-  %s: %s', $item['key'], toString($item['previousValue']));
                    break;
                case 'removed':
                    $resultString[] = sprintf('-  %s: %s', $item['key'], toString($item['previousValue']));
                    break;
                case 'added':
                    $resultString[] = sprintf('+  %s: %s', $item['key'], toString($item['currentValue']));
                    break;
            }

            return implode(PHP_EOL, $resultString);
        }
    }, $tree);

    array_unshift($resultForString, '{');
    array_push($resultForString, '}');

    return implode(PHP_EOL, $resultForString);
}
