<?php

namespace ProjxIO\Lists;

use PHPUnit_Framework_TestCase;

class ReductionsTest extends PHPUnit_Framework_TestCase
{
    public function callbackDataProvider()
    {
        return [
            ['merge', Reductions::merge(), [], [], [[], [], []]],
            ['merge', Reductions::merge(), [1, 2, 3], [1, 2, 3, 4, 5, 6, 7, 8, 9], [[4, 5, 6], [7, 8], [9]]],
            ['merge', Reductions::merge(), [], [4, 5, 6, 7, 8, 9], [[4, 5, 6], [7, 8], [9]]],
            ['implode', Reductions::implode(), null, '', []],
            ['implode', Reductions::implode(), '', '', []],
            ['implode', Reductions::implode(','), null, '', []],
            ['implode', Reductions::implode(','), '', '', []],
            ['implode', Reductions::implode(','), null, ',,', ['', '', '']],
            ['implode', Reductions::implode(',', null, false), null, ',,,', ['', '', '']],
            ['implode', Reductions::implode(','), '', ',,,', ['', '', '']],
            ['implode', Reductions::implode(','), 'a', 'a,,,', ['', '', '']],
            ['implode', Reductions::implode(','), 'a', 'a,b,c,d', ['b', 'c', 'd']],
            ['implode', Reductions::implode(',', Maps::key()), 'a', 'a,0,1,2', ['b', 'c', 'd']],
            ['sum', Reductions::sum(), 0, 6, [1, 2, 3]],
            ['sum', Reductions::sum(), 0, 0, []],
            ['sum', Reductions::sum(), 13, 13, []],
            ['sum', Reductions::sum(), 0, 0, [5, -5]],
            ['sum', Reductions::sum(), 0, 10, [5, -10, 15]],
            ['product', Reductions::product(), null, 6, [1, 2, 3]],
            ['product', Reductions::product(), 1, 1, []],
            ['product', Reductions::product(), 13, 13, []],
            ['product', Reductions::product(), null, -25, [5, -5]],
            ['product', Reductions::product(), null, -750, [5, -10, 15]],
            ['product', Reductions::product(null, false), null, 0, [5, -10, 15]],
            ['ands', Reductions::ands(), true, true, []],
            ['ands', Reductions::ands(), false, false, []],
            ['ands', Reductions::ands(), null, true, [true]],
            ['ands', Reductions::ands(null, false), null, false, [true]],
            ['ands', Reductions::ands(), true, true, [true, true, true]],
            ['ands', Reductions::ands(), true, false, [true, true, false]],
            ['ands', Reductions::ands(), false, false, [true, false, true]],
            ['ands', Reductions::ands(), false, false, [false, true, true]],
            ['ands', Reductions::ands(), false, false, [false, false, false]],
            ['ands', Reductions::ands(), true, false, [true, true, false]],
            ['ors', Reductions::ors(), null, false, []],
            ['ors', Reductions::ors(), true, true, []],
            ['ors', Reductions::ors(), false, false, []],
            ['ors', Reductions::ors(), true, true, [true, true, true]],
            ['ors', Reductions::ors(), true, true, [true, true, false]],
            ['ors', Reductions::ors(), null, true, [true, false, true]],
            ['ors', Reductions::ors(), null, true, [false, true, true]],
            ['ors', Reductions::ors(), null, false, [false, false, false]],
            ['max', Reductions::max(), null, null, []],
            ['max', Reductions::max(), null, 0, [0]],
            ['max', Reductions::max(), null, 99, [0, 10, 99, -99]],
            ['min', Reductions::min(), null, null, []],
            ['min', Reductions::min(), null, 0, [0]],
            ['min', Reductions::min(), null, -99, [0, 10, 99, -99]],
            ['average', Reductions::average(), null, 0, []],
            ['average', Reductions::average(), 10, 10, []],
            ['average', Reductions::average(), 10, 5, [0]],
            ['average', Reductions::average(), null, 2.5, [0, 10, 99, -99]],
            ['average', Reductions::average(), 5, 3, [0, 10, 99, -99]],
        ];
    }

    /**
     * @dataProvider callbackDataProvider
     *
     * @param $method
     * @param $callback
     * @param $expect
     * @param $value
     * @param $key
     */
    public function testCallbacks($method, $callback, $initial, $expect, $value, $key = null)
    {
        $actual = Arrays::reduce($value, $callback, $initial);

        $this->assertEquals($expect, $actual, $method);
    }
}
