<?php

namespace App\Gendiff;

use function App\Gendiff\Formatter\renderPlain;
use function App\Gendiff\Formatter\renderPretty;

function genDiff($pathToFileBefore, $pathToFileAfter, $format = 'pretty')
{
    $beforeDecode = getObjectFromFile($pathToFileBefore);
    $afterDecode  = getObjectFromFile($pathToFileAfter);

    $tree = builderTree($beforeDecode, $afterDecode);

    switch ($format) {
        case 'pretty':
            return renderPretty($tree);
            break;
        case 'plain':
            return renderPlain($tree);
            break;
    }
}
