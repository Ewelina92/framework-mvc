<?php

declare(strict_types=1);

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\DiceModels\Dice;

/**
 * Test cases for the Diceclass.
 */
class DiceTest extends TestCase
{
    /**
     * Try to create the Dice class.
     */
    public function testCreateDiceHandClass()
    {
        $dice = new Dice();
        $this->assertInstanceOf("App\Models\DiceModels\Dice", $dice);
    }

    /**
     * Test the function roll().
     */
    public function testRoll()
    {
        $dice = new Dice(6); // faces, roll
        $res = $dice->roll();

        $resultSpan = boolval($res <= 6 && $res >= 1);

        $this->assertIsInt($res);
        $this->assertTrue($resultSpan);
    }

    /**
     * Test the function getLastRoll().
     */
    public function testGetLastRoll()
    {
        $controller = new Dice(6, 4); // faces, roll
        $res = $controller->getLastRoll();
        $exp = 4;

        $this->assertEquals($exp, $res);
    }
}
