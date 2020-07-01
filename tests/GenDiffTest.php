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

    /**
     * @dataProvider additionProviderForBuilderTree
     */
/*    public function testBuilderTree($first, $second, $expected)
    {
        $this->assertEquals($expected, builderTree($first, $second));
    }

    public function additionProviderForBuilderTree()
    {
        return [
            [
                [
                    'host'    => 'hexlet.io',
                    'timeout' => 50,
                    'proxy'   => '123.234.53.22'
                ],
                [
                    'timeout' => 20,
                    'host'    => 'hexlet.io',
                    'verbose' => true
                ],
                [
                    [
                        'key'          => 'host',
                        'type'         => 'unchanged',
                        'currentValue' => 'hexlet.io'
                    ],
                    [
                        'key'           => 'timeout',
                        'type'          => 'changed',
                        'previousValue' => '50',
                        'currentValue'  => '20'
                    ],
                    [
                        'key'           => 'proxy',
                        'type'          => 'removed',
                        'previousValue' => '123.234.53.22'
                    ],
                    [
                        'key'          => 'verbose',
                        'type'         => 'added',
                        'currentValue' => true
                    ],
                ],
            ],
        ];
    }*/
}
