<?php

declare(strict_types=1);

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Handlers\Game21Handler;
use App\Models\DiceModels\DiceHand;

/**
 * Test cases for the DiceHand class.
 */
class GameTest extends TestCase
{
    /**
     * Try to create the Game21Handler class.
     */
    public function testCreateGameClass()
    {
        $game = new Game21Handler();
        $this->assertInstanceOf("App\Models\Handlers\Game21Handler", $game);
    }

    /**
     * Test the function welcome() in playGame().
     *
     */
    public function testWelcome()
    {
        $game = new Game21Handler();

        $result = $game->playGame();

        $expected = [
            "title" => "21",
            "header" => "Game 21",
            "message" => ("Welcome to the dice game 21! 
                You can maximum bet half of your bitcoins."),
            "playerBitcoin" => 10,
            "pageToRender" => "game21"
        ];

        // renderView($result["pageToRender"], $result);

        $this->assertIsArray($result);
        $this->assertEquals($expected, $result);
    }

    /**
     * Test the function resetGame() in playGame().
     */
    public function testResetGame()
    {
        $game = new Game21Handler();
        request()->merge([
            "reset" => "True"
        ]);

        session([
            "playerBitcoin" => 20,
        ]);

        $result = $game->playGame();

        $expected = [
            "title" => "21",
            "header" => "Game 21",
            "message" => ("Welcome to the dice game 21! 
                You can maximum bet half of your bitcoins."),
            "playerBitcoin" => 10,
            "pageToRender" => "game21"
        ];

        // renderView($result["pageToRender"], $result);

        $this->assertIsArray($result);
        $this->assertEquals($expected, $result);
    }

    /**
     * Test the function startGame() in playGame() when invalid bet first round.
     *
     */
    public function testStartGameInvalidBet()
    {
        $game = new Game21Handler();
        request()->merge([
            "bitcoin" => "6", // valid bet
            "dice" => "1"
        ]);

        $result = $game->playGame();

        $expected = [
            "title" => "21",
            "header" => "Game 21",
            "message" => ("Welcome to the dice game 21! 
                You can maximum bet half of your bitcoins."),
            "playerBitcoin" => 10,
            "pageToRender" => "game21"
        ];


        $this->assertIsArray($result);
        $this->assertEquals($expected, $result);
    }

    /**
     * Test the function startGame() in playGame() when valid bet.
     *
     */
    public function testStartGameValidBet()
    {
        $mockDiceHand = $this->createMock(DiceHand::class);
        $mockDiceHand->method('getSum')
             ->willReturn(10);
        $mockDiceHand->method('graphicLastRoll')
             ->willReturn(["die die-5", "die die-5"]);

        $game = new Game21Handler($mockDiceHand);

        session([
            "rounds" => 1,
            "playerBitcoin" => 10,
            "computerBitcoin" => 100,
        ]);

        request()->merge([
            "bitcoin" => "5", // valid bet
            "dice" => "1"
        ]);

        $result = $game->playGame();

        $expected = [
            "title" => "21",
            "header" => "Player's round",
            "pageToRender" => "game21-roll"
        ];

        // renderView($result["pageToRender"], $result);

        $this->assertIsArray($result);
        $this->assertEquals($expected["header"], $result["header"]);
        $this->assertEquals($expected["pageToRender"], $result["pageToRender"]);
    }

    /**
     * Test the response when player gets exactly 21.
     *
     */
    public function testPlayerExactly21()
    {
        $mockDiceHand = $this->createMock(DiceHand::class);
        $mockDiceHand->method('getSum')
             ->willReturn(10);
        $mockDiceHand->method('graphicLastRoll')
             ->willReturn(["die die-5", "die die-5"]);

        $game = new Game21Handler($mockDiceHand);

        session([
            "rounds" => 2,
            "playerBitcoin" => 10,
            "computerBitcoin" => 100,
            "currentBet" => 0,
            "playerScore" => 11,
            "computerScore" => 0,
            "numDice" => 2,
        ]);

        $result = $game->playGame();

        $expected = [
            "title" => "21",
            "header" => "Congratulations!",
            "pageToRender" => "game21-between"
        ];

        // renderView($result["pageToRender"], $result);

        $this->assertIsArray($result);
        $this->assertEquals($expected["header"], $result["header"]);
        $this->assertEquals($expected["pageToRender"], $result["pageToRender"]);
    }

    /**
     * Test the response when player gets over 21.
     *
     */
    public function testPlayerOver21()
    {
        $mockDiceHand = $this->createMock(DiceHand::class);
        $mockDiceHand->method('getSum')
             ->willReturn(10);
        $mockDiceHand->method('graphicLastRoll')
             ->willReturn(["die die-5", "die die-5"]);

        $game = new Game21Handler($mockDiceHand);

        session([
            "playerBitcoin" => 10,
            "computerBitcoin" => 100,
            "currentBet" => 0,
            "playerScore" => 15,
            "computerScore" => 0,
            "numDice" => 2,
        ]);

        $result = $game->playGame();

        $expected = [
            "title" => "21",
            "header" => "You lost!",
            "pageToRender" => "game21-winner"
        ];

        // renderView($result["pageToRender"], $result);

        $this->assertIsArray($result);
        $this->assertEquals($expected["header"], $result["header"]);
        $this->assertEquals($expected["pageToRender"], $result["pageToRender"]);
    }

    /**
     * Test the response when the computer wins.
     *
     */
    public function testComputerWin()
    {

        $mockDiceHand = $this->createMock(DiceHand::class);
        $mockDiceHand->method('getSum')
             ->willReturn(21);
        $mockDiceHand->method('graphicLastRoll')
             ->willReturn(["die die-5", "die die-5"]);

        $game = new Game21Handler($mockDiceHand);

        session([
            "playerBitcoin" => 10,
            "computerBitcoin" => 100,
            "currentBet" => 0,
            "playerScore" => 15,
            "numDice" => 2,
        ]);

        request()->merge([
            "turn" => "computer",
        ]);

        $result = $game->playGame();

        $expected = [
            "title" => "21",
            "header" => "Result this round",
            "pageToRender" => "game21-winner"
        ];

        // renderView($result["pageToRender"], $result);

        $this->assertIsArray($result);
        $this->assertEquals(1, session("computerWins"));
        $this->assertEquals($expected["header"], $result["header"]);
        $this->assertEquals($expected["pageToRender"], $result["pageToRender"]);
    }

    /**
     * est the response when the computer loses.
     *
     */
    public function testComputerLose()
    {

        $mockDiceHand = $this->createMock(DiceHand::class);
        $mockDiceHand->method('getSum')->willReturn(25);
        $mockDiceHand->method('graphicLastRoll')->willReturn(["die die-5", "die die-5"]);

        $game = new Game21Handler($mockDiceHand);

        session([
            "rounds" => 1 ,
            "computerWins" => 0,
            "playerWins" => 0,
            "playerBitcoin" => 10,
            "computerBitcoin" => 100,
            "currentBet" => 0,
            "playerScore" => 15,
            "numDice" => 2,
        ]);

        request()->merge([
            "turn" => "computer",
        ]);

        $result = $game->playGame();

        $expected = [
            "title" => "21",
            "header" => "Result this round",
            "pageToRender" => "game21-winner"
        ];

        // renderView($result["pageToRender"], $result);

        $this->assertIsArray($result);
        $this->assertEquals(1, session("playerWins"));
        $this->assertEquals($expected["header"], $result["header"]);
        $this->assertEquals($expected["pageToRender"], $result["pageToRender"]);
    }

    /**
     * Test the function startGame() in playGame() when invalid bet, not first round.
     *
     */
    public function testStartGameInvalidBetAgain()
    {
        // session_start();
        $game = new Game21Handler();

        session([
            "rounds" => 2,
            "playerWins" => 1,
            "computerWins" => 0,
            "playerBitcoin" => 10,
            "computerBitcoin" => 100,
        ]);

        request()->merge([
            "nextRound" => "next round",
            "bitcoin" => "6", // invalid bet
        ]);


        $result = $game->playGame();

        $expected = [
            "title" => "21",
            "header" => "Invalid bet!",
            "pageToRender" => "game21-winner"
        ];

        // renderView($result["pageToRender"], $result);

        $this->assertIsArray($result);
        $this->assertEquals($expected["header"], $result["header"]);
        $this->assertEquals($expected["pageToRender"], $result["pageToRender"]);
    }
}
