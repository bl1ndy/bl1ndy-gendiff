<?php

namespace Differ\Cli;

use function Differ\Differ\genDiff;

const MAN = <<<DOC
Generate diff

Usage:
  gendiff (-h|--help)
  gendiff (-v|--version)
  gendiff [--format <fmt>] <firstFile> <secondFile>

Options:
  -h --help                     Show this screen
  -v --version                  Show version
  --format <fmt>                Report format [default: stylish]

DOC;

function run(): void
{
    $args = \Docopt::handle(MAN, array('version' => 'Generate diff 0.1.0'));

    $firstFilePath = $args['<firstFile>'];
    $secondFilePath = $args['<secondFile>'];
    $format = $args['--format'];

    print_r(genDiff($firstFilePath, $secondFilePath, $format));
}
