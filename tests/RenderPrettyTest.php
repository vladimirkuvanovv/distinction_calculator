<?php


namespace App\Tests;

use PHPUnit\Framework\TestCase;

use function App\Gendiff\genDiff;

class RenderPrettyTest extends TestCase
{
    public function testRenderPretty()
    {
        $expected = file_get_contents(__DIR__ . '/fixtures/rightPrettyTree');
        $this->assertEquals($expected, genDiff(
            __DIR__ . '/fixtures/bef.json',
            __DIR__ . '/fixtures/aft.json'
        ));

        $expected = file_get_contents(__DIR__ . '/fixtures/rightPretty');
        $this->assertEquals($expected, genDiff(
            __DIR__ . '/fixtures/before.json',
            __DIR__ . '/fixtures/after.json'
        ));

        $wrong_expected = file_get_contents(__DIR__ . '/fixtures/wrongPrettyTree');
        $this->assertNotEquals($wrong_expected, genDiff(
            __DIR__ . '/fixtures/bef.json',
            __DIR__ . '/fixtures/aft.json'
        ));
    }
}
