<?php

namespace App\Tests;

use PHPUnit\Framework\TestCase;

use function App\Gendiff\genDiff;
use function App\Gendiff\getContent;
use function App\Gendiff\compareArrays;
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
     * @dataProvider additionProviderForCompareArrays
     */
    public function testCompareArrays($first, $second, $expected)
    {
        $this->assertEquals($expected, compareArrays($first, $second));
    }

    /**
     * @dataProvider additionProvider
     * @param $data
     */
    public function testResultOfDifference($data)
    {
        $expected = file_get_contents(__DIR__ . '/fixtures/right');
        $this->assertEquals($expected, getResultOfDifference($data));
    }

    public function additionProviderForCompareArrays()
    {
        return [
            [
                [
                    'host' => 'hexlet.io',
                    'timeout' => 50,
                    'proxy' => '123.234.53.22'
                ],
                [
                    'timeout' => 20,
                    'host' => 'hexlet.io',
                    'verbose' => true
                ],
                [
                    'host' => ['value' => 'hexlet.io'],
                    'timeout' => ['value' => 20, 'diff' => 50],
                    'proxy' => ['diff' => '123.234.53.22', 'value' => null],
                    'verbose' => ['value' => true, 'diff' => null]
                ],
            ],
        ];
    }

    public function additionProvider()
    {
        return [
            [
                [
                    'host' => ['value' => 'hexlet.io'],
                    'timeout' => ['value' => 20, 'diff' => 50],
                    'proxy' => ['diff' => '123.234.53.22', 'value' => null],
                    'verbose' => ['value' => true, 'diff' => null]
                ],
            ],
        ];
    }
}
