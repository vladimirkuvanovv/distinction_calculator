<?php

namespace App\Gendiff;

use function App\Gendiff\Formatters\renderDiff;

function genDiff($pathToFileBefore, $pathToFileAfter, $format = 'pretty')
{
    try {
        $beforeDecode = getObjectFromFile($pathToFileBefore);
        $afterDecode  = getObjectFromFile($pathToFileAfter);

        $tree = builderTree($beforeDecode, $afterDecode);

        return renderDiff($format, $tree);
    } catch (\Exception $e) {
        echo $e->getMessage();
    }
}
