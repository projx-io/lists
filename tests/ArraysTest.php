<?php

namespace ProjxIO\Lists;

use PHPUnit_Framework_TestCase;

class ArraysTest extends PHPUnit_Framework_TestCase
{
    public static function toUpperByRef(&$value, $key)
    {
        return $value = strtoupper($value);
    }

    public static function toLowerByRef(&$value, $key)
    {
        return $value = strtolower($value);
    }

    public static function toUpper($value, $key)
    {
        return strtoupper($value);
    }

    public static function toLower($value, $key)
    {
        return strtolower($value);
    }

    public static function equals($expect)
    {
        return function ($actual) use ($expect) {
            return $actual === $expect;
        };
    }

    private static $upperByRef;
    private static $lowerByRef;

    private static $upper;
    private static $lower;

    public function dataForEachProvider()
    {
        self::$upperByRef = function (&$value) {
            return $value = strtoupper($value);
        };

        self::$lowerByRef = function (&$value) {
            return $value = strtolower($value);
        };

        $upper = ['A', 'B', 'C'];
        $lower = ['a', 'b', 'c'];
        $mixed = ['a', 'B', 'c'];

        return [
            [self::$upperByRef, [], []],
            [self::$upperByRef, $lower, $upper],
            [self::$upperByRef, $mixed, $upper],
            [self::$lowerByRef, $mixed, $lower],
            [self::$lowerByRef, $upper, $lower],
            [[$this, 'toUpperByRef'], $lower, $upper],
            [[$this, 'toUpperByRef'], $mixed, $upper],
            [[$this, 'toLowerByRef'], $mixed, $lower],
            [[$this, 'toLowerByRef'], $upper, $lower],
            [__CLASS__ . '::toUpperByRef', $lower, $upper],
            [__CLASS__ . '::toUpperByRef', $mixed, $upper],
            [__CLASS__ . '::toLowerByRef', $mixed, $lower],
            [__CLASS__ . '::toLowerByRef', $upper, $lower],
        ];
    }

    public function dataMapProvider()
    {
        self::$upper = function ($value) {
            return $value = strtoupper($value);
        };

        self::$lower = function ($value) {
            return $value = strtolower($value);
        };

        $upper = ['A', 'B', 'C'];
        $lower = ['a', 'b', 'c'];
        $mixed = ['a', 'B', 'c'];

        return [
            [self::$upper, [], []],
            [self::$upper, $lower, $upper],
            [self::$upper, $mixed, $upper],
            [self::$lower, $mixed, $lower],
            [self::$lower, $upper, $lower],
            [[$this, 'toUpper'], $lower, $upper],
            [[$this, 'toUpper'], $mixed, $upper],
            [[$this, 'toLower'], $mixed, $lower],
            [[$this, 'toLower'], $upper, $lower],
            [__CLASS__ . '::toUpper', $lower, $upper],
            [__CLASS__ . '::toUpper', $mixed, $upper],
            [__CLASS__ . '::toLower', $mixed, $lower],
            [__CLASS__ . '::toLower', $upper, $lower],
        ];
    }

    public function dataFilterProvider()
    {
        return [
            [$this->equals('a'), [], []],
            [$this->equals('a'), ['a', 'b', 'c'], ['a']],
            [$this->equals('b'), ['a', 'b', 'c'], [1 => 'b']],
            [$this->equals('c'), ['a', 'b', 'c'], [2 => 'c']],
            [$this->equals('d'), ['a', 'b', 'c'], []],
            [$this->equals('a'), ['A' => 'a', 'B' => 'b', 'C' => 'c'], ['A' => 'a']],
            [$this->equals('b'), ['A' => 'a', 'B' => 'b', 'C' => 'c'], ['B' => 'b']],
            [$this->equals('c'), ['A' => 'a', 'B' => 'b', 'C' => 'c'], ['C' => 'c']],
            [$this->equals('d'), ['A' => 'a', 'B' => 'b', 'C' => 'c'], []],
        ];
    }

    public function dataMapFilterProvider()
    {
        return [
            [__CLASS__ . '::toUpper', $this->equals('A'), [], []],
            [__CLASS__ . '::toUpper', $this->equals('A'), ['a', 'b', 'c'], ['a']],
            [__CLASS__ . '::toUpper', $this->equals('B'), ['a', 'b', 'c'], [1 => 'b']],
            [__CLASS__ . '::toUpper', $this->equals('C'), ['a', 'b', 'c'], [2 => 'c']],
            [__CLASS__ . '::toUpper', $this->equals('D'), ['a', 'b', 'c'], []],
            [__CLASS__ . '::toUpper', $this->equals('A'), ['A' => 'a', 'B' => 'b', 'C' => 'c'], ['A' => 'a']],
            [__CLASS__ . '::toUpper', $this->equals('B'), ['A' => 'a', 'B' => 'b', 'C' => 'c'], ['B' => 'b']],
            [__CLASS__ . '::toUpper', $this->equals('C'), ['A' => 'a', 'B' => 'b', 'C' => 'c'], ['C' => 'c']],
            [__CLASS__ . '::toUpper', $this->equals('D'), ['A' => 'a', 'B' => 'b', 'C' => 'c'], []],
        ];
    }

    public function dataRenameProvider()
    {
        $function = function ($index) {
            return function ($array) use ($index) {
                return $array[$index];
            };
        };

        return [
            [
                $function(0),
                [['red', 3], ['green', 2], ['blue', 1]],
                ['red' => ['red', 3], 'green' => ['green', 2], 'blue' => ['blue', 1]]
            ],
            [
                $function(1),
                [['red', 3], ['green', 2], ['blue', 1]],
                [3 => ['red', 3], 2 => ['green', 2], 1 => ['blue', 1]]
            ]
        ];
    }

    public function dataGroupProvider()
    {
        $function = function ($index) {
            return function ($array) use ($index) {
                return $array[$index];
            };
        };

        return [
            [
                $function(0),
                [], []],
            [
                $function(0),
                [['red', 1], ['green', 1], ['blue', 1], ['red', 2], ['green', 2]],
                [
                    'red' => [0 => ['red', 1], 3 => ['red', 2],],
                    'green' => [1 => ['green', 1], 4 => ['green', 2],],
                    'blue' => [2 => ['blue', 1],]
                ]
            ],
            [
                $function(1),
                [['red', 1], ['green', 1], ['blue', 1], ['red', 2], ['green', 2]],
                [1 => [0 => ['red', 1], 1 => ['green', 1], 2 => ['blue', 1]], 2 => [3 => ['red', 2], 4 => ['green', 2]]]
            ]
        ];
    }

    public function dataGroupsProvider()
    {
        $function = function ($index) {
            return function ($array) use ($index) {
                return $array[$index];
            };
        };

        return [
            [
                [$function(1), $function(2)],
                [], []],
            [
                [$function(1), $function(2)],
                [
                    0 => [0, 'red', 'medium'],
                    1 => [1, 'blue', 'medium'],
                    2 => [2, 'red', 'medium'],
                    3 => [3, 'red', 'large'],
                    4 => [4, 'blue', 'large'],
                    5 => [5, 'green', 'medium'],
                    6 => [6, 'red', 'short'],
                    7 => [7, 'red', 'medium'],
                    8 => [8, 'blue', 'medium'],
                ],
                [
                    'red' => [
                        'medium' => [
                            0 => [0, 'red', 'medium'],
                            2 => [2, 'red', 'medium'],
                            7 => [7, 'red', 'medium'],
                        ],
                        'large' => [
                            3 => [3, 'red', 'large'],
                        ],
                        'short' => [
                            6 => [6, 'red', 'short'],
                        ],
                    ],
                    'blue' => [
                        'medium' => [
                            1 => [1, 'blue', 'medium'],
                            8 => [8, 'blue', 'medium'],
                        ],
                        'large' => [
                            4 => [4, 'blue', 'large'],
                        ],
                    ],
                    'green' => [
                        'medium' => [
                            5 => [5, 'green', 'medium'],
                        ],
                    ],
                ]
            ],
            [
                [$function(2), $function(1)],
                [
                    0 => [0, 'red', 'medium'],
                    1 => [1, 'blue', 'medium'],
                    2 => [2, 'red', 'medium'],
                    3 => [3, 'red', 'large'],
                    4 => [4, 'blue', 'large'],
                    5 => [5, 'green', 'medium'],
                    6 => [6, 'red', 'short'],
                    7 => [7, 'red', 'medium'],
                    8 => [8, 'blue', 'medium'],
                ],
                [
                    'medium' => [
                        'red' => [
                            0 => [0, 'red', 'medium'],
                            2 => [2, 'red', 'medium'],
                            7 => [7, 'red', 'medium'],
                        ],
                        'blue' => [
                            1 => [1, 'blue', 'medium'],
                            8 => [8, 'blue', 'medium'],
                        ],
                        'green' => [
                            5 => [5, 'green', 'medium'],
                        ],
                    ],
                    'large' => [
                        'red' => [
                            3 => [3, 'red', 'large'],
                        ],
                        'blue' => [
                            4 => [4, 'blue', 'large'],
                        ],
                    ],
                    'short' => [
                        'red' => [
                            6 => [6, 'red', 'short'],
                        ],
                    ]
                ]
            ]
        ];
    }

    public function dataReduceProvider()
    {
        $sum = function ($a, $b) {
            return $a + $b;
        };

        $concat = function ($a, $b) {
            return $a . $b;
        };

        return [
            [$sum, 0, [], 0],
            [$sum, 1, [], 1],
            [$sum, 0, [0, 1, 2, 3], 6],
            [$sum, 0, [0, 4, 5, 6], 15],
            [$sum, 10, [1, 4, 5, 6], 26],
            [$concat, '', ['a', 'b', 'c'], 'abc'],
            [$concat, '', ['d', 'e', 'f'], 'def'],
            [$concat, 'A', ['d', 'e', 'f'], 'Adef'],
        ];
    }

    public function dataSortProvider()
    {
        return [
            [null, [1, 2, 3], [1, 2, 3]],
            [null, [1 => 3, 2 => 2, 3 => 1], [3 => 1, 2 => 2, 1 => 3]],
            [null, ['a' => 'A'], ['a' => 'A']],
            [null, ['a' => 'A', 'b' => 'B'], ['a' => 'A', 'b' => 'B']],
            [null, ['b' => 'B', 'a' => 'A'], ['a' => 'A', 'b' => 'B']],
            [null, ['b' => 'B', 'c' => 'C', 'a' => 'A'], ['a' => 'A', 'b' => 'B', 'c' => 'C']],
            [Maps::key(), ['b' => 0, 'c' => 0, 'a' => 0], ['a' => 0, 'b' => 0, 'c' => 0]],
            [Maps::key(), [], []],
        ];
    }

    /**
     * @dataProvider dataForEachProvider
     *
     * @param $callback
     * @param $array
     * @param $expect
     * @param $keys
     */
    public function testEach($callback, $array, $expect, $keys = true)
    {
        Arrays::each($array, $callback, $keys);

        $this->assertEquals($expect, $array);
        $this->assertEquals(json_encode($expect), json_encode($array));
    }

    /**
     * @dataProvider dataMapProvider
     *
     * @param $callback
     * @param $array
     * @param $expect
     */
    public function testMap($callback, $array, $expect)
    {
        $actual = Arrays::map($array, $callback);

        $this->assertEquals($expect, $actual);
        $this->assertEquals(json_encode($expect), json_encode($actual));
    }

    /**
     * @dataProvider dataFilterProvider
     *
     * @param $callback
     * @param $array
     * @param $expect
     */
    public function testFilter($callback, $array, $expect)
    {
        $actual = Arrays::filter($array, $callback);

        $this->assertEquals($expect, $actual);
        $this->assertEquals(json_encode($expect), json_encode($actual));
    }

    /**
     * @dataProvider dataMapFilterProvider
     *
     * @param $map
     * @param $filter
     * @param $array
     * @param $expect
     */
    public function testMapFilter($map, $filter, $array, $expect)
    {
        $actual = Arrays::mapFilter($array, $map, $filter);

        $this->assertEquals($expect, $actual);
        $this->assertEquals(json_encode($expect), json_encode($actual));
    }

    /**
     * @dataProvider dataRenameProvider
     *
     * @param $callback
     * @param $array
     * @param $expect
     */
    public function testRename($callback, $array, $expect)
    {
        $actual = Arrays::rename($array, $callback);

        $this->assertEquals($expect, $actual);
        $this->assertEquals(json_encode($expect), json_encode($actual));
    }

    /**
     * @dataProvider dataGroupProvider
     *
     * @param $callback
     * @param $array
     * @param $expect
     */
    public function testGroup($callback, $array, $expect)
    {
        $actual = Arrays::group($array, $callback);

        $this->assertEquals($expect, $actual);
        $this->assertEquals(json_encode($expect), json_encode($actual));
    }

    /**
     * @dataProvider dataReduceProvider
     *
     * @param $callback
     * @param $initial
     * @param $array
     * @param $expect
     */
    public function testReduce($callback, $initial, $array, $expect)
    {
        $actual = Arrays::reduce($array, $callback, $initial);

        $this->assertEquals($expect, $actual);
        $this->assertEquals(json_encode($expect), json_encode($actual));
    }

    /**
     * @dataProvider dataSortProvider
     *
     * @param $callback
     * @param $array
     * @param $expect
     */
    public function testSort($callback, $array, $expect)
    {
        $actual = Arrays::sort($array, $callback);

        $this->assertEquals($expect, $actual);
        $this->assertEquals(json_encode($expect), json_encode($actual));
    }

    /**
     * @dataProvider dataGroupsProvider
     *
     * @param $callbacks
     * @param $array
     * @param $expect
     */
    public function testGroups($callbacks, $array, $expect)
    {
        $actual = Arrays::groups($array, $callbacks);

        $this->assertEquals($expect, $actual);
        $this->assertEquals(json_encode($expect), json_encode($actual));
    }
}
