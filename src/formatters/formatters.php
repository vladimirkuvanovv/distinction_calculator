<?php

namespace App\Gendiff\Formatters;

function renderDiff($format, $tree)
{
    switch ($format) {
        case 'pretty':
            return renderPretty($tree);
            break;
        case 'plain':
            return renderPlain($tree);
            break;
        case 'json':
            return renderJson($tree);
            break;
        default:
            throw new \Exception("Unknown format {$format}");
    }
}
