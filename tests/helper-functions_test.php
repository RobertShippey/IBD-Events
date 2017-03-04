<?php

require_once('IBDE-plugin/helper-functions.php');

/**
 * @covers ::cardinal_direction
 */
final class cardinal_direction_test extends \PHPUnit_Framework_TestCase
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

/**
 * @covers ::ibde_starts_with
 */
final class ibde_starts_with_test extends \PHPUnit_Framework_TestCase
{
    public function test_ibde_starts_with_is_working()
    {
        $this->assertTrue(ibde_starts_with('',''));
        $this->assertTrue(ibde_starts_with('Hello World!','Hell'));
        $this->assertTrue(ibde_starts_with('IBD Events','IBD E'));

        $this->assertFalse(ibde_starts_with('',' '));
        $this->assertFalse(ibde_starts_with('Hello World!','World'));
        $this->assertFalse(ibde_starts_with('IBD Events','s'));
    }
}
