<?php

declare(strict_types=1);

namespace App\Models\Handlers;

use App\Models\DiceModels\{
    GraphicalDice,
    DiceHand
};

/**
 * Class Game21Handler.
 */
class Game21Handler
{

    private $diceHand;

    public function __construct(DiceHand $diceHand = null)
    {
        $this->diceHand = $diceHand;
        if ($diceHand === null) {
            $this->diceHand = new DiceHand();
        }
    }

    private function welcome(): array
    {
        $data = [
            "title" => "21",
            "header" => "Game 21",
            "message" => ("Welcome to the dice game 21! 
                You can maximum bet half of your bitcoins."),
            "playerBitcoin" => session("playerBitcoin"),
            "pageToRender" => "game21"
        ];
        return $data;
    }

    private function playerTurn(): array
    {
        $data = [
            "title" => "21",
            "header" => "Player's round",
            "message" => "You threw:",
            "pageToRender" => "game21-roll"
        ];

        // $diceHand = new DiceHand(); // start game with 1-2 dice
        for ($i = 0; $i < session("numDice"); $i++) {
            $this->diceHand->addDice(new GraphicalDice());
        }
        $this->diceHand->roll(); // roll the dice

        $data["diceHandRoll"] = "";
        // graphic representation of the dice
        for ($i = 0; $i < session("numDice"); $i++) {
            $data["diceHandRoll"] .= "<span class=\"{$this->diceHand->graphicLastRoll()[$i]}\" ></span>";
        }

        $data["roundSum"] = $this->diceHand->getSum(); // get sum of all rolls
        session()->put("playerScore", ($data["roundSum"] + session("playerScore"))); // add to total score
        $data["totalScorePlayer"] = session("playerScore"); // make total score available in $data

        // check if exactly 21: congrats
        if (intval($data["totalScorePlayer"]) == 21) {
            $data = [
                "title" => "21",
                "header" => "Congratulations!",
                "message" => "You got 21, now it's the computers turn.",
                "pageToRender" => "game21-between"
            ];
            return $data;
        }

        // check if over 21: game over
        if (intval($data["totalScorePlayer"]) > 21) {
            $data = [
                "title" => "21",
                "header" => "You lost!",
                "message" => "You got over 21, the computer wins this round."
            ];

            return $this->showResult("computer", $data);
        }

        return $data;
    }

    private function computerTurn(): array
    {
        $data = [
            "title" => "21",
            "header" => "Result this round",
        ];

        session()->put("computerScore", 0);

        // $diceHand = new DiceHand(); // start game with 1-2 dice
        for ($i = 0; $i < session("numDice"); $i++) {
            $this->diceHand->addDice(new GraphicalDice());
        }

        while (session("computerScore") < 21) {
            $this->diceHand->roll(); // roll the dice
            $data["roundSum"] = $this->diceHand->getSum(); // get the sum of the round
            session()->put("computerScore", (session("computerScore") + $data["roundSum"]));
            $data["totalScoreComputer"] = session("computerScore");
        }

        if (session("computerScore") === 21) {
            $data["message"] = "The computer got 21, it won!";
            return $this->showResult("computer", $data);
        }

        $data["message"] = "You won! Computer is over 21.";
        return $this->showResult("player", $data);
    }

    private function showResult(string $winner, array $data): array
    {
        if (session()->has('rounds')) { // keep track of amount of rounds
            session()->put('rounds', (intval(session('rounds')) + 1));
        }

        // initialize necessary variables after first round
        if (!session()->has('rounds')) {
            session()->put('rounds', 1);
            session()->put('playerWins', 0);
            session()->put('computerWins', 0);
        }

        // keep track of winners and movement of bitcoin
        if ($winner === "player") {
            session()->put('playerWins', (1 + intval(session('playerWins'))));
            session()->put("playerBitcoin", (intval(session('playerBitcoin')) + intval(session("currentBet"))));
            session()->put("computerBitcoin", (intval(session('computerBitcoin')) - intval(session("currentBet"))));
        } elseif ($winner === "computer") {
            session()->put('computerWins', (1 + intval(session('computerWins'))));
            session()->put("playerBitcoin", (intval(session('playerBitcoin')) - intval(session("currentBet"))));
            session()->put("computerBitcoin", (intval(session('computerBitcoin')) + intval(session("currentBet"))));
        }

        // reset round score
        session()->put("playerScore", 0);
        session()->put("computerScore", 0);

        // make sure $data has all necessary information for the view
        $data["title"] = "21";
        $data["rounds"] = session('rounds');
        $data["playerWins"] = session('playerWins');
        $data["computerWins"] = session('computerWins');
        $data["playerBitcoin"] = session("playerBitcoin");
        $data["computerBitcoin"] = session("computerBitcoin");
        $data["pageToRender"] = "game21-winner";

        return $data;
    }

    private function resetGame(): array
    {
        session()->flush();
        // start up bitcoins accounts by start of game
        if (!session()->has("playerBitcoin")) {
            session()->put("playerBitcoin", 10);
            session()->put("computerBitcoin", 100);
        }
        return $this->welcome();
    }

    private function checkBet(int $bitcointBet): bool
    {
        return (bool) !(intval($bitcointBet) > (0.5 * session("playerBitcoin")));
    }

    private function startGame(int $firstRound = 1): array
    {
        // get how much the player wants to bet
        $bitcoinBet = request()->has('bitcoin') ? intval(htmlentities(request()->input('bitcoin'))) : null;

        // if not valid bet, return to welcome page again
        if (!$this->checkBet($bitcoinBet)) {
            if ($firstRound) {
                return $this->welcome();
            }

            $data = [
                "title" => "21",
                "header" => "Invalid bet!",
                "message" => "You can only bet maximum 50% of you bitcoins, please try again!",
                "rounds" => session('rounds'),
                "playerWins" => session('playerWins'),
                "computerWins" => session('computerWins'),
                "playerBitcoin" => session("playerBitcoin"),
                "computerBitcoin" => session("computerBitcoin"),
                "pageToRender" => "game21-winner"
            ];

            return $data;
        }

        // save the current bet
        session()->put("currentBet", $bitcoinBet);

        // check how many dice
        $dice = request()->has('dice') ? htmlentities(request()->input('dice')) : null;

        if ($dice) {
            // set the amount of dice for the game session
            session()->put("numDice", intval($dice));
        }

        // players start with score 0
        session()->put("playerScore", 0);
        session()->put("computerScore", 0);

        return $this->playerTurn();
    }

    public function playGame(): array
    {
        // start up bitcoins accounts by start of game
        if (!session()->has("playerBitcoin")) {
            session()->put("playerBitcoin", 10);
            session()->put("computerBitcoin", 100);
        }

        // reset the game
        if (request()->has('reset') && request()->input('reset') === "True") {
            return $this->resetGame();
        }

        // start first round
        if (request()->has('dice')) {
            return $this->startGame();
        }

        // start next round
        if (request()->has('nextRound')) {
            return $this->startGame(0);
        }

        // no game in progress
        if (!session()->has('playerScore')) {
            return $this->welcome();
        }

        // computer's turn
        if (request()->has('turn')) {
            return $this->computerTurn();
        }

        // game in progress
        return $this->playerTurn();
    }
}
