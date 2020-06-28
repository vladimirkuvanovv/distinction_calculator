<?php

namespace App\Tests;

use PHPUnit\Framework\TestCase;

use function App\Gendiff\getContent;
use function App\Gendiff\parseJson;
use function App\Gendiff\parseYaml;

class ParseTest extends TestCase
{
    /**
     * @dataProvider additionProvider
     */
    public function testParse($data)
    {
        $content = getContent(__DIR__ . '/fixtures/before.yaml');
        $this->assertEquals($data, (array) parseYaml($content));

        $content = getContent(__DIR__ . '/fixtures/before.json');
        $this->assertEquals($data, (array) parseJson($content));
    }

    public function additionProvider()
    {
        return [
            [
                [
                    'host'    => 'hexlet.io',
                    'timeout' => 50,
                    'proxy'   => '123.234.53.22'
                ],
            ],
        ];
    }
}
