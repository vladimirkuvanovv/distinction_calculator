<?php

namespace App\Tests;

use PHPUnit\Framework\TestCase;

use function App\Gendiff\genDiff;
use function App\Gendiff\getContent;
use function App\Gendiff\builderTree;

class GenDiffTest extends TestCase
{
    public function testGenDiff()
    {
        $expected = file_get_contents(__DIR__ . '/fixtures/rightPretty');
        $this->assertEquals($expected, genDiff(
            __DIR__ . '/fixtures/before.json',
            __DIR__ . '/fixtures/after.json'
        ));

        $wrong_expected = file_get_contents(__DIR__ . '/fixtures/wrongPretty');
        $this->assertNotEquals($wrong_expected, genDiff(
            __DIR__ . '/fixtures/before.json',
            __DIR__ . '/fixtures/after.json'
        ));
    }

    public function testGetContent()
    {
        $expected = file_get_contents(__DIR__ . '/fixtures/before.json');
        $this->assertEquals($expected, getContent(__DIR__ . '/fixtures/before.json'));
    }

    public function testException()
    {
        $this->expectException(\Exception::class);

        getContent('/fixtures/before.json');

        $this->expectException(\Exception::class);

        genDiff(
            __DIR__ . '/fixtures/before.json',
            __DIR__ . '/fixtures/after.json',
            'pre'
        );
    }

    /**
     * @dataProvider additionProviderForBuilderTree
     */
    public function testBuilderTree($first, $second, $expected)
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
    }
}
