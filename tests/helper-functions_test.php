<?php

require_once('IBDE-plugin/helper-functions.php');

use PHPUnit\Framework\TestCase;

/**
 * @covers cardinal_direction
 */
final class cardinal_direction_test extends TestCase
{
    /**
     * @dataProvider additionProvider
     */
    public function test_cardinal_direction_returns_short_name($a, $expected)
    {
        $this->assertArrayHasKey('short_name', cardinal_direction($a));
        $this->assertEquals($expected, cardinal_direction($a)['short_name']);
    }

    public function additionProvider()
    {
        return [
            [0, 'N'],
            [20, 'N'],
            [40, 'NE'],
            [90, 'E'],
            [-90, 'W'],
            [360, 'N'],
            [361, 'N']
        ];
    }

    public function test_cardinal_direction_only_accepts_numbers() {
        $this->assertFalse(cardinal_direction('x'));
        $this->assertFalse(cardinal_direction(null));
        $this->assertFalse(cardinal_direction([1,2,3]));

        $this->assertFalse(cardinal_direction('x', 'x'));
        $this->assertFalse(cardinal_direction(null, null));
        $this->assertFalse(cardinal_direction([1,2,3], [1,2,3]));
    }
}
