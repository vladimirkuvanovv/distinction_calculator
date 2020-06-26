<?php

namespace App\Gendiff;

use Docopt;

use function App\Gendiff\genDiff;

const DOC = <<<'DOCOPT'

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

function getGenDiffInfo()
{
    $result = Docopt::handle(DOC, ['version' => '1.0.0rc2']);
    $format = $result->args['--format'];
    $argv1 = $result->args['<firstFile>'] ?? '';
    $argv2 = $result->args['<secondFile>'] ?? '';

    if ($argv1 && $argv2) {
        try {
            print_r(genDiff($argv1, $argv2, $format));
        } catch (\Exception $e) {
            echo $e->getMessage() . "\n";
        }
    }
}
