<?php

declare(strict_types=1);

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Handlers\YatzyHandler;

/**
 * Test cases for the YatzyHandler class.
 */
class YatzyHandlerTest extends TestCase
{

    /**
     * Test the function welcome
     *
     */
    public function testWelcome()
    {
        $game = new YatzyHandler();

        $result = $game->playGame();

        $expected = [
            "header" => "Yatzy",
            "pageToRender" => "yatzy-welcome"
        ];

        // renderView($result["pageToRender"], $result);

        $this->assertIsArray($result);
        $this->assertEquals($expected["header"], $result["header"]);
        $this->assertEquals($expected["pageToRender"], $result["pageToRender"]);
    }

    /**
     * Test the function startGame.
     *
     */
    public function testStartGame()
    {
        $game = new YatzyHandler();

        request()->merge([
            "startYatzy" => "start"
        ]);

        $result = $game->playGame();

        $expected = [
            "header" => "Roll number: 1",
            "pageToRender" => "yatzy-round"
        ];

        // renderView($result["pageToRender"], $result);

        $this->assertIsArray($result);
        $this->assertEquals($expected["header"], $result["header"]);
        $this->assertEquals($expected["pageToRender"], $result["pageToRender"]);
    }

    /**
     * Test the continuing game after first round.
     *
     */
    public function testContinueGame()
    {
        $game = new YatzyHandler();

        session([
            "diceToRollYatzy" => 5,
            "yatzyGame" => "playing",
            "turnNumberYatzy" => 1,
            "savedDiceValuesYatzy" => [],
            "allValuesFromTurn" => [],
            "resultSlotsYatzy" => [
                1 => null,
                2 => null,
                3 => null,
                4 => null,
                5 => null,
                6 => null,
            ]
        ]);
        request()->merge([
            "rollAgain" => "roll",
            "ydice1" => "6",
        ]);

        $result = $game->playGame();

        $expected = [
            "header" => "Roll number: 2",
            "pageToRender" => "yatzy-round"
        ];

        // renderView($result["pageToRender"], $result);

        $this->assertIsArray($result);
        $this->assertEquals($expected["header"], $result["header"]);
        $this->assertEquals($expected["pageToRender"], $result["pageToRender"]);
    }

    /**
     * Test the response when all dice are saved
     *
     */
    public function testAllDiceSaved()
    {
        $game = new YatzyHandler();

        session([
            "diceToRollYatzy" => 5,
            "yatzyGame" => "playing",
            "turnNumberYatzy" => 1,
            "savedDiceValuesYatzy" => [],
            "allValuesFromTurn" => [6,6,6,6,6],
            "resultSlotsYatzy" => [
                1 => null,
                2 => null,
                3 => null,
                4 => null,
                5 => null,
                6 => null,
            ]
        ]);

        request()->merge([
            "rollAgain" => "roll",
            "ydice1" => "6",
            "ydice2" => "6",
            "ydice3" => "6",
            "ydice4" => "6",
            "ydice5" => "6",
        ]);

        $result = $game->playGame();

        $expected = [
            "header" => "Place your points",
            "pageToRender" => "yatzy-points"
        ];

        // renderView($result["pageToRender"], $result);

        $this->assertIsArray($result);
        $this->assertEquals($expected["header"], $result["header"]);
        $this->assertEquals($expected["pageToRender"], $result["pageToRender"]);
    }

    /**
     * Test the response when no available slot
     *
     */
    public function testAllDiceSavedNoAvailable()
    {
        $game = new YatzyHandler();

        session([
            "diceToRollYatzy" => 5,
            "yatzyGame" => "playing",
            "turnNumberYatzy" => 1,
            "savedDiceValuesYatzy" => [],
            "allValuesFromTurn" => [6,6,6,6,6],
            "resultSlotsYatzy" => [
                1 => null,
                2 => null,
                3 => null,
                4 => null,
                5 => null,
                6 => 12,
            ]
        ]);

        request()->merge([
            "rollAgain" => "roll",
            "ydice1" => "6",
            "ydice2" => "6",
            "ydice3" => "6",
            "ydice4" => "6",
            "ydice5" => "6",
        ]);

        $result = $game->playGame();

        $expected = [
            "header" => "Place your points",
            "pageToRender" => "yatzy-points"
        ];

        // renderView($result["pageToRender"], $result);

        $this->assertIsArray($result);
        $this->assertEquals($expected["header"], $result["header"]);
        $this->assertEquals($expected["pageToRender"], $result["pageToRender"]);
    }


    /**
     * Test the assigning of point
     *
     */
    public function testAssignPoints()
    {
        $game = new YatzyHandler();

        session([
            "diceToRollYatzy" => 5,
            "yatzyGame" => "playing",
            "turnNumberYatzy" => 1,
            "savedDiceValuesYatzy" => [],
            "allValuesFromTurn" => [6,6,6,6,6],
            "resultSlotsYatzy" => [
                1 => null,
                2 => null,
                3 => null,
                4 => null,
                5 => null,
                6 => 12,
            ]
        ]);
        request()->merge([
            "assignPoints" => "assign",
            "choice" => "2:25"
        ]);

        $result = $game->playGame();

        $expected = [
            "header" => "Roll number: 1",
            "pageToRender" => "yatzy-round"
        ];

        // renderView($result["pageToRender"], $result);

        $this->assertIsArray($result);
        $this->assertEquals($expected["header"], $result["header"]);
        $this->assertEquals($expected["pageToRender"], $result["pageToRender"]);
    }

    /**
     * Test the assigning of points the last time.
     *
     */
    public function testAssignPointsFinal()
    {
        $game = new YatzyHandler();

        session([
            "diceToRollYatzy" => 5,
            "yatzyGame" => "playing",
            "turnNumberYatzy" => 1,
            "savedDiceValuesYatzy" => [],
            "allValuesFromTurn" => [6,6,6,6,6],
            "resultSlotsYatzy" => [
                1 => 5,
                2 => 10,
                3 => 15,
                4 => 20,
                5 => null,
                6 => 30,
            ]
        ]);
        request()->merge([
            "assignPoints" => "assign",
            "choice" => "5:25"
        ]);

        $result = $game->playGame();

        $expected = [
            "header" => "Thank you for playing Yatzy!",
            "pageToRender" => "yatzy-ending"
        ];

        // renderView($result["pageToRender"], $result);

        $this->assertIsArray($result);
        $this->assertEquals($expected["header"], $result["header"]);
        $this->assertEquals($expected["pageToRender"], $result["pageToRender"]);
    }


    /**
     * Test the response when continuing after the third throw.
     *
     */
    public function testContinueAfterThirdTurn()
    {
        $game = new YatzyHandler();

        session([
            "diceToRollYatzy" => 5,
            "yatzyGame" => "playing",
            "turnNumberYatzy" => 3,
            "savedDiceValuesYatzy" => [],
            "allValuesFromTurn" => [6,6,6,6,6],
            "resultSlotsYatzy" => [
                1 => 5,
                2 => 10,
                3 => 15,
                4 => 20,
                5 => null,
                6 => 30,
            ]
        ]);
        request()->merge([
            "checkTurnResult" => "test",
        ]);

        $result = $game->playGame();

        $expected = [
            "header" => "Place your points",
            "pageToRender" => "yatzy-points"
        ];

        // renderView($result["pageToRender"], $result);

        $this->assertIsArray($result);
        $this->assertEquals($expected["header"], $result["header"]);
        $this->assertEquals($expected["pageToRender"], $result["pageToRender"]);
    }
}
