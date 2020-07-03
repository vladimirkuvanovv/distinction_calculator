<?php

namespace Gendiff\Tests;

use PHPUnit\Framework\TestCase;

use function Gendiff\Differ\genDiff;
use function Gendiff\Parsers\getContent;
use function Gendiff\Parsers\getDecodedProperties;

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
    public function testDecodedJsonProperties($expected)
    {
        $extensionFile = pathinfo($this->beforeJsonFilePath, PATHINFO_EXTENSION);
        $this->assertEquals($expected, getDecodedProperties($extensionFile, getContent($this->beforeJsonFilePath)));
    }

    /**
     * @dataProvider additionProviderForBuilderTree
     */
    public function testDecodedYamlProperties($expected)
    {
        $extensionFile = pathinfo($this->beforeYmlFilePath, PATHINFO_EXTENSION);
        $this->assertEquals($expected, getDecodedProperties($extensionFile, getContent($this->beforeYmlFilePath)));
    }

    public function additionProviderForBuilderTree()
    {
        return [
            [
                [
                    "common" => [
                        "setting1" => "Value 1",
                        "setting2" => "200",
                        "setting3" => true,
                        "setting6" => [
                            "key" => "value"
                        ],
                    ],
                    "group1" => [
                        "baz" => "bas",
                        "foo" => "bar"
                    ],
                    "group2" => [
                        "abc" => "12345"
                    ],
                ]
            ],
        ];
    }
}
