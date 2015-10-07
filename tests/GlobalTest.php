<?php

namespace ProjxIO\Lists;

use PHPUnit_Framework_TestCase;

class GlobalTest extends PHPUnit_Framework_TestCase
{
    public function dataTypeProvider()
    {
        $map = 'ProjxIO\Lists\Map';
        $set = 'ProjxIO\Lists\Set';

        return [
            [$map, map()],
            [$set, set()],
            [$map, map([])],
            [$set, set([])],
            [$map, map(null)],
            [$set, set(null)],
            [$map, map([1, 2, 3])],
            [$set, set([1, 2, 3])],
            [$map, map(['a' => 1, 'b' => 2, 'c' => 3])],
            [$set, set(['a' => 1, 'b' => 2, 'c' => 3])],
        ];
    }

    /**
     * @dataProvider dataTypeProvider
     *
     * @param $type
     * @param $list
     */
    public function testMap($type, $list)
    {
        $this->assertInstanceOf($type, $list);
    }
}
