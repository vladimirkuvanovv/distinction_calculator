<?php

namespace Gendiff;

use Docopt;
use function Gendiff\getDifferenceFiles;

function getGenDiffInfo()
{
    $doc = <<<'DOCOPT'

Generate diff

Usage:
  gendiff (-h | --help)
  gendiff (-v | --version)
  gendiff [--format <fmt>] <firstFile> <secondFile>

Options:
  -h --help       Show this screen
  -v --version    Show version and exit
  --format <fmt>  Report format [default: pretty]

DOCOPT;

    $result = Docopt::handle($doc, ['version' => '1.0.0rc2']);

    $argv1 = '';
    $argv2 = '';

    foreach ($result as $k => $v) {
        if ($k == '<firstFile>') {
            $argv1 = $v;
        }

        if ($k == '<secondFile>') {
            $argv2 = $v;
        }
    }

    if (!empty($argv1) && !empty($argv2)) {
        echo getDifferenceFiles($argv1, $argv2);
    } else {
        echo 'wrong arguments';
    }
}
