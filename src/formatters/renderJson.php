<?php

namespace Gendiff\Formatters;

function renderJson(array $tree)
{
    return json_encode($tree, JSON_FORCE_OBJECT);
}
