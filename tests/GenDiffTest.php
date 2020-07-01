<?php

namespace Gendiff\Tests;

use PHPUnit\Framework\TestCase;

use function Gendiff\genDiff;
use function Gendiff\getContent;
use function Gendiff\getDecodedProperties;

class GenDiffTest extends TestCase
{
    protected $rightPrettyFilePath = '';
    protected $beforeJsonFilePath  = '';
    protected $afterJsonFilePath   = '';

    protected $rightPlainFilePath = '';
    protected $beforeYmlFilePath  = '';
    protected $afterYmlFilePath   = '';

    protected $rightJsonFilePath = '';

    protected function setUp()
    {
        $this->rightPrettyFilePath = __DIR__ . '/fixtures/rightPretty';
        $this->beforeJsonFilePath  = __DIR__ . '/fixtures/before.json';
        $this->afterJsonFilePath   = __DIR__ . '/fixtures/after.json';

        $this->rightPlainFilePath = __DIR__ . '/fixtures/rightPlainTree';
        $this->beforeYmlFilePath  = __DIR__ . '/fixtures/before.yml';
        $this->afterYmlFilePath   = __DIR__ . '/fixtures/after.yml';

        $this->rightJsonFilePath = __DIR__ . '/fixtures/rightJsonTree.json';
    }

    public function testGenDiffPrettyFormat()
    {
        $expected = file_get_contents($this->rightPrettyFilePath);
        $this->assertEquals($expected, genDiff($this->beforeJsonFilePath, $this->afterJsonFilePath));
    }

    public function testGenDiffPlainFormat()
    {
        $expected = file_get_contents($this->rightPlainFilePath);
        $this->assertEquals($expected, genDiff($this->beforeYmlFilePath, $this->afterYmlFilePath, 'plain'));
    }

    public function testGenDiffJsonFormat()
    {
        $expected = file_get_contents($this->rightJsonFilePath);
        $this->assertEquals($expected, genDiff($this->beforeJsonFilePath, $this->afterJsonFilePath, 'json'));
    }

    public function testException()
    {
        $this->expectException(\Exception::class);

        getContent(__DIR__ . '/fixtures/befor.json');
    }
}
