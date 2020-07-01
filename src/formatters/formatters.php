<?php

namespace Gendiff\Formatters;

function renderDiff($format, $tree)
{
    try {
        switch ($format) {
            case 'pretty':
                return renderPretty($tree);
            case 'plain':
                return renderPlain($tree);
            case 'json':
                return renderJson($tree);
            default:
                throw new \Exception("Unknown format {$format}");
        }
    } catch (\Exception $e) {
        echo $e->getMessage();
    }
}
