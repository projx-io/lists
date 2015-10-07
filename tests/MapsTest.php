<?php

namespace ProjxIO\Lists;

use PHPUnit_Framework_TestCase;

class MapsTest extends PHPUnit_Framework_TestCase
{
    public function callbackDataProvider()
    {
        return [
            ['get', Maps::get(), 'a', 'a'],
            ['get', Maps::get(), 'b', 'b'],
            ['key', Maps::key(), 'A', 'a', 'A'],
            ['key', Maps::key(), 'B', 'b', 'B'],
            ['equals', Maps::equals('a'), true, 'a'],
            ['equals', Maps::equals('b'), false, 'a'],
            ['not', Maps::not(Maps::equals('a')), false, 'a'],
            ['not', Maps::not(Maps::equals('b')), true, 'a'],
            ['atLeast', Maps::atLeast(5), true, 5],
            ['atLeast', Maps::atLeast(5), true, 10],
            ['atLeast', Maps::atLeast(10), false, 5],
            ['atMost', Maps::atMost(15), true, 15],
            ['atMost', Maps::atMost(15), true, 10],
            ['atMost', Maps::atMost(10), false, 15],
            ['ands', Maps::ands(Maps::equals('a'), Maps::equals('a')), true, 'a'],
            ['ands', Maps::ands(Maps::equals('a'), Maps::equals('b')), false, 'a'],
            ['ands', Maps::ands(Maps::equals('a'), Maps::equals('b')), false, 'b'],
            ['ands', Maps::ands(Maps::equals('a'), Maps::equals('a')), false, 'b'],
            ['ors', Maps::ors(Maps::equals('a'), Maps::equals('a')), true, 'a'],
            ['ors', Maps::ors(Maps::equals('a'), Maps::equals('b')), true, 'a'],
            ['ors', Maps::ors(Maps::equals('a'), Maps::equals('b')), true, 'b'],
            ['ors', Maps::ors(Maps::equals('a'), Maps::equals('a')), false, 'b'],
            ['moreThan', Maps::moreThan(5), false, 5],
            ['moreThan', Maps::moreThan(5), true, 10],
            ['moreThan', Maps::moreThan(10), false, 5],
            ['lessThan', Maps::lessThan(15), false, 15],
            ['lessThan', Maps::lessThan(15), true, 10],
            ['lessThan', Maps::lessThan(10), false, 15],
            ['has', Maps::has('a'), true, ['a' => 'b']],
            ['has', Maps::has('b'), false, ['a' => 'b']],
            ['has', Maps::has('a', 'b'), true, ['a' => ['b' => 'c']]],
            ['has', Maps::has('a', 'c'), false, ['a' => ['b' => 'c']]],
            ['has', Maps::has('a'), false, []],
            ['get', Maps::get('a'), 'b', ['a' => 'b']],
            ['get', Maps::get('a'), ['b' => 'c'], ['a' => ['b' => 'c']]],
            ['isValueOf', Maps::isValueOf(['a', 'b']), true, 'a'],
            ['isValueOf', Maps::isValueOf(['a', 'b']), true, 'b'],
            ['isValueOf', Maps::isValueOf(['a', 'b']), false, 'c'],
            ['isValueOf', Maps::isValueOf(['a', 'b']), false, 'd'],
            ['isKeyOf', Maps::isKeyOf(['a' => 'A', 'b' => 'B']), true, 'a'],
            ['isKeyOf', Maps::isKeyOf(['a' => 'A', 'b' => 'B']), true, 'b'],
            ['isKeyOf', Maps::isKeyOf(['a' => 'A', 'b' => 'B']), false, 'c'],
            ['isKeyOf', Maps::isKeyOf(['a' => 'A', 'b' => 'B']), false, 'd'],
            ['isString', Maps::isString(), false, null],
            ['isString', Maps::isString(), false, 5],
            ['isString', Maps::isString(), false, 0.5],
            ['isString', Maps::isString(), false, false],
            ['isString', Maps::isString(), true, 'a'],
            ['isString', Maps::isString(), false, []],
            ['isString', Maps::isString(), false, (object)[]],
            ['isArray', Maps::isArray(), false, null],
            ['isArray', Maps::isArray(), false, 5],
            ['isArray', Maps::isArray(), false, 0.5],
            ['isArray', Maps::isArray(), false, false],
            ['isArray', Maps::isArray(), false, 'a'],
            ['isArray', Maps::isArray(), true, []],
            ['isArray', Maps::isArray(), false, (object)[]],
            ['isObject', Maps::isObject(), false, null],
            ['isObject', Maps::isObject(), false, 5],
            ['isObject', Maps::isObject(), false, 0.5],
            ['isObject', Maps::isObject(), false, false],
            ['isObject', Maps::isObject(), false, 'a'],
            ['isObject', Maps::isObject(), false, []],
            ['isObject', Maps::isObject(), true, (object)[]],
            ['isInteger', Maps::isInteger(), false, null],
            ['isInteger', Maps::isInteger(), true, 5],
            ['isInteger', Maps::isInteger(), false, 0.5],
            ['isInteger', Maps::isInteger(), false, false],
            ['isInteger', Maps::isInteger(), false, 'a'],
            ['isInteger', Maps::isInteger(), false, []],
            ['isInteger', Maps::isInteger(), false, (object)[]],
            ['isBoolean', Maps::isBoolean(), false, null],
            ['isBoolean', Maps::isBoolean(), false, 5],
            ['isBoolean', Maps::isBoolean(), false, 0.5],
            ['isBoolean', Maps::isBoolean(), true, false],
            ['isBoolean', Maps::isBoolean(), true, true],
            ['isBoolean', Maps::isBoolean(), false, 'a'],
            ['isBoolean', Maps::isBoolean(), false, []],
            ['isBoolean', Maps::isBoolean(), false, (object)[]],
            ['isNumeric', Maps::isNumeric(), false, null],
            ['isNumeric', Maps::isNumeric(), true, 5],
            ['isNumeric', Maps::isNumeric(), true, 0.5],
            ['isNumeric', Maps::isNumeric(), true, '0.5'],
            ['isNumeric', Maps::isNumeric(), false, false],
            ['isNumeric', Maps::isNumeric(), false, 'a'],
            ['isNumeric', Maps::isNumeric(), false, []],
            ['isNumeric', Maps::isNumeric(), false, (object)[]],
            ['isTrue', Maps::isTrue(), false, null],
            ['isTrue', Maps::isTrue(), false, 0],
            ['isTrue', Maps::isTrue(), false, 5],
            ['isTrue', Maps::isTrue(), false, 0.5],
            ['isTrue', Maps::isTrue(), false, '0.5'],
            ['isTrue', Maps::isTrue(), false, ''],
            ['isTrue', Maps::isTrue(), false, false],
            ['isTrue', Maps::isTrue(), true, true],
            ['isTrue', Maps::isTrue(), false, 'a'],
            ['isTrue', Maps::isTrue(), false, []],
            ['isTrue', Maps::isTrue(), false, ['a']],
            ['isTrue', Maps::isTrue(), false, (object)[]],
            ['isTruthy', Maps::isTruthy(), false, null],
            ['isTruthy', Maps::isTruthy(), false, 0],
            ['isTruthy', Maps::isTruthy(), true, 5],
            ['isTruthy', Maps::isTruthy(), true, 0.5],
            ['isTruthy', Maps::isTruthy(), true, '0.5'],
            ['isTruthy', Maps::isTruthy(), false, ''],
            ['isTruthy', Maps::isTruthy(), false, false],
            ['isTruthy', Maps::isTruthy(), true, true],
            ['isTruthy', Maps::isTruthy(), true, 'a'],
            ['isTruthy', Maps::isTruthy(), false, []],
            ['isTruthy', Maps::isTruthy(), true, ['a']],
            ['isTruthy', Maps::isTruthy(), true, (object)[]],
            ['isFalse', Maps::isFalse(), false, null],
            ['isFalse', Maps::isFalse(), false, 0],
            ['isFalse', Maps::isFalse(), false, 5],
            ['isFalse', Maps::isFalse(), false, 0.5],
            ['isFalse', Maps::isFalse(), false, '0.5'],
            ['isFalse', Maps::isFalse(), false, ''],
            ['isFalse', Maps::isFalse(), true, false],
            ['isFalse', Maps::isFalse(), false, true],
            ['isFalse', Maps::isFalse(), false, 'a'],
            ['isFalse', Maps::isFalse(), false, []],
            ['isFalse', Maps::isFalse(), false, ['a']],
            ['isFalse', Maps::isFalse(), false, (object)[]],
            ['isFalsey', Maps::isFalsey(), true, null],
            ['isFalsey', Maps::isFalsey(), true, 0],
            ['isFalsey', Maps::isFalsey(), false, 5],
            ['isFalsey', Maps::isFalsey(), false, 0.5],
            ['isFalsey', Maps::isFalsey(), false, '0.5'],
            ['isFalsey', Maps::isFalsey(), true, ''],
            ['isFalsey', Maps::isFalsey(), true, false],
            ['isFalsey', Maps::isFalsey(), false, true],
            ['isFalsey', Maps::isFalsey(), false, 'a'],
            ['isFalsey', Maps::isFalsey(), true, []],
            ['isFalsey', Maps::isFalsey(), false, ['a']],
            ['isFalsey', Maps::isFalsey(), false, (object)[]],
            ['regex', Maps::regex('/cde/'), true, 'abcdefg'],
            ['regex', Maps::regex('/xyz/'), false, 'abcdefg'],
            ['parse', Maps::parse('/.(c.e)./'), ['bcdef', 'cde'], 'abcdefg'],
            ['parse', Maps::parse('/.(..)(.)f/'), ['bcdef', 'cd', 'e'], 'abcdefg'],
            ['size', Maps::size(), 0, []],
            ['size', Maps::size(), 2, ['a', 'b']],
            ['size', Maps::size(), 3, ['a', 'b', 'c']],
            ['reduce sum', Maps::reduce(0, Reductions::sum()), 6, [1, 2, 3]],
            ['reduce sum', Maps::reduce(0, Reductions::sum()), 0, []],
            ['reduce sum', Maps::reduce(13, Reductions::sum()), 13, []],
            ['reduce sum', Maps::reduce(0, Reductions::sum()), 0, [5, -5]],
            ['reduce sum', Maps::reduce(0, Reductions::sum()), 10, [5, -10, 15]],
            ['reduce product', Maps::reduce(1, Reductions::product()), 6, [1, 2, 3]],
            ['reduce product', Maps::reduce(1, Reductions::product()), 1, []],
            ['reduce product', Maps::reduce(13, Reductions::product()), 13, []],
            ['reduce product', Maps::reduce(1, Reductions::product()), -25, [5, -5]],
            ['reduce product', Maps::reduce(1, Reductions::product()), -750, [5, -10, 15]],
            ['reduce merge', Maps::reduce([], Reductions::merge()), [], [[], [], []]],
            ['reduce merge', Maps::reduce([1, 2, 3], Reductions::merge()), [1, 2, 3, 4, 5, 6, 7, 8, 9], [[4, 5, 6], [7, 8], [9]]],
            ['reduce merge', Maps::reduce([], Reductions::merge()), [4, 5, 6, 7, 8, 9], [[4, 5, 6], [7, 8], [9]]],
            ['reduce implode', Maps::reduce('', Reductions::implode()), '', []],
            ['reduce implode', Maps::reduce('', Reductions::implode(',')), '', []],
            ['reduce implode', Maps::reduce('', Reductions::implode(',')), ',,,', ['', '', '']],
            ['reduce implode', Maps::reduce('a', Reductions::implode(',')), 'a,,,', ['', '', '']],
            ['reduce implode', Maps::reduce('a', Reductions::implode(',')), 'a,b,c,d', ['b', 'c', 'd']],
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
    public function testCallbacks($method, $callback, $expect, $value, $key = null)
    {
        $actual = call_user_func($callback, $value, $key);

        $this->assertEquals($expect, $actual, $method);
    }
}
