<?php

namespace App\Gendiff;

function genDiff($pathToFileBefore, $pathToFileAfter, $format = '')
{
    $beforeDecode = getObjectFromFile($pathToFileBefore, $format);
    $afterDecode  = getObjectFromFile($pathToFileAfter, $format);

    return getResultOfDifference(compareArrays($beforeDecode, $afterDecode));
}
