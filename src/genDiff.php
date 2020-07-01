<?php

namespace Gendiff;

use function Gendiff\Formatters\renderDiff;

function genDiff($pathToFileBefore, $pathToFileAfter, $format = 'pretty')
{
    $beforeDecode = getDecodedProperties($pathToFileBefore);
    $afterDecode  = getDecodedProperties($pathToFileAfter);

    $tree = builderTree($beforeDecode, $afterDecode);

    return renderDiff($format, $tree);
}
