<?php


namespace App\Tests;

use PHPUnit\Framework\TestCase;

use function App\Gendiff\genDiff;

class GenDiffTreeTest extends TestCase
{
    public function testGenDiffTree()
    {
        $expected = file_get_contents(__DIR__ . '/fixtures/rightTree');
        $this->assertEquals($expected, genDiff(__DIR__ . '/fixtures/bef.json', __DIR__ . '/fixtures/aft.json'));

        /*$wrong_expected = file_get_contents(__DIR__ . '/fixtures/wrong');
        $this->assertNotEquals($wrong_expected, genDiff(
            __DIR__ . '/fixtures/before.json',
            __DIR__ . '/fixtures/after.json'
        ));*/
    }
}