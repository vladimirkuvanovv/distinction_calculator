<?php

namespace Gendiff\Differ;

use function Gendiff\Builder\builderTree;
use function Gendiff\Formatters\Formatters\renderDiff;
use function Gendiff\Parsers\getContent;
use function Gendiff\Parsers\getDecodedProperties;

function genDiff($pathToFileBefore, $pathToFileAfter, $format = 'pretty')
{
    $extensionFileBefore = pathinfo($pathToFileBefore, PATHINFO_EXTENSION);
    $extensionFileAfter = pathinfo($pathToFileAfter, PATHINFO_EXTENSION);

    $contentFromFileBefore = getContent($pathToFileBefore);
    $contentFromFileAfter = getContent($pathToFileAfter);

    $beforeDecode = getDecodedProperties($extensionFileBefore, $contentFromFileBefore);
    $afterDecode  = getDecodedProperties($extensionFileAfter, $contentFromFileAfter);

    $tree = builderTree($beforeDecode, $afterDecode);

    return renderDiff($format, $tree);
}
