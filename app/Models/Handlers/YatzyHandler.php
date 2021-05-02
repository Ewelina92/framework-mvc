<?php

declare(strict_types=1);

namespace App\Models\Handlers;

use App\Models\DiceModels\{
    Dice,
    GraphicalDice,
    DiceHand
};

/**
 * Class YatzyHandler.
 */
class YatzyHandler
{
    private $diceHand;

    public function __construct(DiceHand $diceHand = null)
    {
        $this->diceHand = $diceHand;
        if ($diceHand === null) {
            $this->diceHand = new DiceHand();
        }
    }

    /**
     * Function that welcomes the user to the game when no game in progress.
     *
     * @return array
     */
    private function welcome(): array
    {
        return [
            "title" => "Yatzy",
            "header" => "Yatzy",
            "message" => ("Welcome to Yatzy!
                You play with 5 dice, and each turn you can roll maximum 3 times.
                The goal is to to get as many points as possible to fill the slots of
                1, 2, 3, 4, 5 and 6. If you can't fill a slot, you're forced to fill one
                with zero points. A sum of 63 or more gives 50 extra points as a bonus."),
            "pageToRender" => "yatzy-welcome"
        ];
    }

    /**
     * Function that handles logic for each turn.
     *
     * @return array
     */
    private function doTurn(): array
    {
        $data = [
            "title" => "Yatzy",
            "header" => "Roll number: " . session("turnNumberYatzy"),
            "message" => "You rolled: ",
            "savedDiceValues" => null,
            "amountOfDice" => 5,
            "pageToRender" => "yatzy-round",
            "turn" => session("turnNumberYatzy"),
            "slot1" => session("resultSlotsYatzy")[1],
            "slot2" => session("resultSlotsYatzy")[2],
            "slot3" => session("resultSlotsYatzy")[3],
            "slot4" => session("resultSlotsYatzy")[4],
            "slot5" => session("resultSlotsYatzy")[5],
            "slot6" => session("resultSlotsYatzy")[6],
        ];

        // $diceHand = new DiceHand();

        for ($i = 0; $i < session("diceToRollYatzy"); $i++) {
            $this->diceHand->addDice(new GraphicalDice());
        }

        $this->diceHand->roll(); // roll dice

        if (session()->has('savedDiceValuesYatzy')) {
            foreach (session("savedDiceValuesYatzy") as $value) {
                $this->diceHand->addDice(new GraphicalDice($value));
            }
        }

        // reset
        session()->put("diceToRollYatzy", 5);
        session()->put("savedDiceValuesYatzy", []);

        // display the result of the roll
        $data["diceHandRoll"] = "";
        // graphic representation of the dice
        for ($i = 0; $i < 5; $i++) {
            $data["diceHandRoll"] .= "<span class=\"{$this->diceHand->graphicLastRoll()[$i]}\" ></span>";
        }

        $diceValues = $this->diceHand->getDiceValues();
        session()->put("allValuesFromTurn", $diceValues);

        $diceValueCount = count($diceValues);

        for ($i = 0; $i < $diceValueCount; $i++) {
            $data["diceValue" . $i] = $diceValues[$i];
        }

        return $data;
    }

    /**
     * Function that handles the result of one roll.
     *
     * @return array
     */
    private function checkRoll(): array
    {
        session()->put("turnNumberYatzy", session('turnNumberYatzy') + 1);

        // check if any die should be saved and save values
        for ($i = 1; $i < 6; $i++) {
            if (request()->has("ydice" . $i)) {
                $temp = session('savedDiceValuesYatzy');
                array_push($temp, intval(request()->input("ydice" . $i)));
                session()->put('savedDiceValuesYatzy', $temp);
                // one fewer die to roll next turn
                session()->put('diceToRollYatzy', session('diceToRollYatzy') - 1);
            }
        }

        // skip rolls if all already saved
        if (count(session("savedDiceValuesYatzy")) === 5) {
            return $this->getAvailableSlots();
        }

        return $this->doTurn();
    }

    /**
     * Function that returns available alternatives for placing points after a roll.
     *
     * @return array
     */
    private function getAvailableSlots(): array
    {
        $availableSlots = [];

        // check values from turn and check if corresponding slot is available
        foreach (session("allValuesFromTurn") as $value) {
            if (session("resultSlotsYatzy")[$value] ===  null) {
                // get possible combinations as key/value pairs
                if (!isset($availableSlots[$value])) {
                    $availableSlots[$value] = $value;
                    continue;
                }
                $availableSlots[$value] += $value;
            }
        }

        // if none of the dice match a free slot
        if (count($availableSlots) === 0) {
            foreach (session("resultSlotsYatzy") as $key => $value) {
                if ($value === null) {
                    // get possible combinations as key/value pairs
                    $availableSlots[$key] = 0;
                }
            }
        }

        return [
            "title" => "Yatzy",
            "header" => "Place your points",
            "message" => "These are the available options:",
            "pageToRender" => "yatzy-points",
            "options" => $availableSlots,
            "slot1" => session("resultSlotsYatzy")[1],
            "slot2" => session("resultSlotsYatzy")[2],
            "slot3" => session("resultSlotsYatzy")[3],
            "slot4" => session("resultSlotsYatzy")[4],
            "slot5" => session("resultSlotsYatzy")[5],
            "slot6" => session("resultSlotsYatzy")[6]
        ];
    }

    /**
     * Function that handles logic when assigning points.
     *
     * @return array
     */
    private function assignPoints(): array
    {
        $keyVal = explode(":", request()->input("choice"));
        $temp = session("resultSlotsYatzy");
        $temp[intval($keyVal[0])] = intval($keyVal[1]);
        session()->put("resultSlotsYatzy", $temp);

        // check if end of game
        foreach (session("resultSlotsYatzy") as $value) {
            if ($value === null) {
                session()->put("diceToRollYatzy", 5); // set dice to 5 again
                session()->put("turnNumberYatzy", 1); // set to first turn again
                session()->put("savedDiceValuesYatzy", []); // set to no saved dice
                return $this->doTurn(); // start a new turn
            }
        }
        return $this->checkBonus();
    }

    /**
     * Function that check if bonus points should be applied.
     *
     * @return array
     */
    private function checkBonus(): array
    {
        session()->put("sum", array_sum(session("resultSlotsYatzy")));
        session()->put("bonus", 0);

        if (session("sum") >= 63) {
            session()->put("bonus", 50);
        }

        $totalScore = session("sum") + session("bonus");
        return $this->showEnding($totalScore);
    }

    /**
     * Function that shows the end result of the game.
     *
     * @return array
     */
    private function showEnding(int $totalScore): array
    {
        $data = [
            "title" => "Yatzy",
            "header" => "Thank you for playing Yatzy!",
            "message" => ("Your sum is: " . session("sum") . "<br>Your bonus is: " . session("bonus") .
            "<br>So your final score is: " . $totalScore),
            "pageToRender" => "yatzy-ending"
        ];

        session()->flush();

        return $data;
    }

    /**
     * Function that sets up necessary information in SESSION.
     *
     * @return array
     */
    private function setupGame(): array
    {
        session([
            "diceToRollYatzy" => 5,
            "yatzyGame" => "playing",
            "turnNumberYatzy" => 1,
            "resultSlotsYatzy" => [
                1 => null,
                2 => null,
                3 => null,
                4 => null,
                5 => null,
                6 => null
            ]
        ]);

        return $this->doTurn();
    }

    /**
     * Function that handles main game play.
     *
     * @return array
     */
    public function playGame(): array
    {
        // user clicked continue after assigning points
        if (request()->has("assignPoints")) {
            return $this->assignPoints();
        }

        // user clicked continue after third turn
        if (request()->has("checkTurnResult")) {
            return $this->getAvailableSlots();
        }

        // user clicked start game in welcome()
        if (request()->has("startYatzy")) {
            return $this->setupGame();
        }

        // user clicked continue after first or second turn
        if (request()->has("rollAgain")) {
            return $this->checkRoll();
        }

        // no game in progress, show welcome
        return $this->welcome();
    }
}
