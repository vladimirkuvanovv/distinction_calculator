<?php

namespace Gendiff;

use \Docopt;

function getGenDiffInfo()
{
    $doc = <<<'DOCOPT'

Generate diff

Usage:
  gendiff (-h | --help)
  gendiff (-v | --version)

Options:
  -h --help                Show this screen
  --version                Show version and exit

DOCOPT;

    $result = Docopt::handle($doc, array('version' => '1.0.0rc2'));
    foreach ($result as $k => $v)
        echo $k . ': ' . json_encode($v) . PHP_EOL;
}
