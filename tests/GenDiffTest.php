<?php

namespace App\Tests;

use PHPUnit\Framework\TestCase;

use function App\Gendiff\genDiff;
use function App\Gendiff\getContent;

class GenDiffTest extends TestCase
{
    public function testGenDiff()
    {
        $expected = file_get_contents(__DIR__ . '/fixtures/fixture');
        $this->assertEquals($expected, genDiff(__DIR__ . '/fixtures/before.json', __DIR__ . '/fixtures/after.json'));
    }

    public function testGetContent()
    {
        $expected = file_get_contents(__DIR__ . '/fixtures/before.json');
        $this->assertEquals($expected, getContent(__DIR__ . '/fixtures/before.json'));
    }

    public function testGetContent()
    {
        $expected = file_get_contents(__DIR__ . '/fixtures/before.json');
        $this->assertEquals($expected, getContent(__DIR__ . '/fixtures/before.json'));
    }
}
