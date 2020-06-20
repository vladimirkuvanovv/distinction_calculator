<?php

namespace App\Tests;

use PHPUnit\Framework\TestCase;

use function App\Gendiff\genDiff;
use function App\Gendiff\getContent;
use function App\Gendiff\builderTree;
use function App\Gendiff\getResultOfDifference;

class GenDiffTest extends TestCase
{
    public function testGenDiff()
    {
        $expected = file_get_contents(__DIR__ . '/fixtures/right');
        $this->assertEquals($expected, genDiff(__DIR__ . '/fixtures/before.json', __DIR__ . '/fixtures/after.json'));

        $wrong_expected = file_get_contents(__DIR__ . '/fixtures/wrong');
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
    }

    /**
     * @dataProvider additionProviderForbuilderTree
     */
    public function testbuilderTree($first, $second, $expected)
    {
        $this->assertEquals($expected, builderTree($first, $second));
    }

    public function additionProviderForbuilderTree()
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
                        'node'  => 'host',
                        'type'  => 'unchanged',
                        'value' => 'hexlet.io'
                    ],
                    [
                        'node'  => 'timeout',
                        'type'  => 'changed',
                        'prevValue' => '50',
                        'value' => '20'
                    ],
                    [
                        'node'  => 'proxy',
                        'type'  => 'removed',
                        'value' => '123.234.53.22'
                    ],
                    [
                        'node'  => 'verbose',
                        'type'  => 'added',
                        'value' => true
                    ],
                ],
            ],
        ];
    }
}
