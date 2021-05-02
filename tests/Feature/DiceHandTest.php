<?php

declare(strict_types=1);

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\DiceModels\{
    DiceHand,
    Dice,
    GraphicalDice
};

/**
 * Test cases for the DiceHand class.
 */
class DiceHandTest extends TestCase
{
    /**
     * Try to create the Dice class.
     */
    public function testCreateDiceHandClass()
    {
        $diceHand = new DiceHand();
        $this->assertInstanceOf("App\Models\DiceModels\DiceHand", $diceHand);
    }

      /**
     * Test the function addDice() with getSum() and getDiceValues().
     */
    public function testAddDice()
    {
        $diceHand = new DiceHand();
        $die1 = new Dice(6, 2); // value = 2
        $die2 = new Dice(6, 3); // value = 3

        $diceHand->addDice($die1);
        $diceHand->addDice($die2);

        $result = $diceHand->getSum();
        $expected = 5; // total sum of values

        $result2 = $diceHand->getDiceValues();
        $expected2 = [2, 3];

        $this->assertIsInt($result);
        $this->assertEquals($expected, $result);
        $this->assertEquals($expected2, $result2);
    }

    /**
     * Test the function roll().
     */
    public function testRoll()
    {
        $diceHand = new DiceHand();
        $die1 = new Dice(6, 2); // value = 2
        $die2 = new Dice(6, 3); // value = 3
        $die3 = new Dice(6, 4); // value = 4

        $diceHand->addDice($die1);
        $diceHand->addDice($die2);
        $diceHand->addDice($die3);

        $diceHand->roll();

        $result = $diceHand->getSum();

        $resultSpan = boolval($result <= 18 && $result >= 3);

        $this->assertIsInt($result);
        $this->assertTrue($resultSpan);
    }

    /**
     * Test the function getLastRoll().
     */
    public function testGetLastRoll()
    {
        $diceHand = new DiceHand();
        $die1 = new Dice(6, 2); // value = 2
        $die2 = new Dice(6, 3); // value = 3
        $die3 = new Dice(6, 4); // value = 4

        $diceHand->addDice($die1);
        $diceHand->addDice($die2);
        $diceHand->addDice($die3);

        $res = $diceHand->getLastRoll();
        $exp = "2, 3, 4,  = 9";

        $this->assertEquals($exp, $res);
    }

    /**
     * Test the function graphicLastRoll().
     */
    public function testGraphicLastRoll()
    {
        $diceHand = new DiceHand();
        $die1 = new GraphicalDice(2); // value = 2
        $die2 = new GraphicalDice(3); // value = 3
        $die3 = new GraphicalDice(4); // value = 4

        $diceHand->addDice($die1);
        $diceHand->addDice($die2);
        $diceHand->addDice($die3);

        $res = $diceHand->graphicLastRoll();
        $exp = ["die die-2", "die die-3", "die die-4"];

        $this->assertEquals($exp, $res);
    }
}
