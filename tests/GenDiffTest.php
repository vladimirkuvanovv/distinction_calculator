<?php

namespace App\Tests;

use PHPUnit\Framework\TestCase;

use function App\Gendiff\genDiff;

class GenDiffTest extends TestCase
{
    public function testGenDiff()
    {
        $expected = file_get_contents(__DIR__ . '/fixtures/fixture');
        $this->assertEquals($expected, genDiff(__DIR__ . '/fixtures/before.json', __DIR__ . '/fixtures/after.json'));
    }
}
