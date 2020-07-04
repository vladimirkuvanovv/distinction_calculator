<?php

namespace Gendiff\Tests;

use PHPUnit\Framework\TestCase;

use function Gendiff\Differ\genDiff;

class GenDiffTest extends TestCase
{
    protected $format = '';
    protected $rightPrettyFilePath = '';
    protected $beforeJsonFilePath  = '';
    protected $afterJsonFilePath   = '';

    protected $rightPlainFilePath = '';
    protected $beforeYmlFilePath  = '';
    protected $afterYmlFilePath   = '';

    protected $rightJsonFilePath = '';

    protected function setUp()
    {
        $this->format = 'pretty';
        $this->rightPrettyFilePath = __DIR__ . '/fixtures/rightPretty';
        $this->beforeJsonFilePath  = __DIR__ . '/fixtures/before.json';
        $this->afterJsonFilePath   = __DIR__ . '/fixtures/after.json';

        $this->rightPlainFilePath = __DIR__ . '/fixtures/rightPlainTree';
        $this->beforeYmlFilePath  = __DIR__ . '/fixtures/before.yml';
        $this->afterYmlFilePath   = __DIR__ . '/fixtures/after.yml';

        $this->rightJsonFilePath = __DIR__ . '/fixtures/rightJsonTree.json';
    }

    public function testGenDiff()
    {
        $expected = file_get_contents($this->rightPrettyFilePath);
        $this->assertEquals($expected, genDiff($this->beforeJsonFilePath, $this->afterJsonFilePath, $this->format));

        $this->format = 'plain';
        $expected = file_get_contents($this->rightPlainFilePath);
        $this->assertEquals($expected, genDiff($this->beforeYmlFilePath, $this->afterYmlFilePath, $this->format));

        $this->format = 'json';
        $expected = file_get_contents($this->rightJsonFilePath);
        $this->assertEquals($expected, genDiff($this->beforeJsonFilePath, $this->afterJsonFilePath, $this->format));
    }

    public function testException()
    {
        $this->expectException(\Exception::class);

        genDiff(__DIR__ . '/fixtures/befor.js', __DIR__ . '/fixtures/after.js');
    }
}
