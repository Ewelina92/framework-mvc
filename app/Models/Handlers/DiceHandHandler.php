<?php

declare(strict_types=1);

namespace App\Models\Handlers;

use App\Models\DiceModels\GraphicalDice;
use App\Models\DiceModels\DiceHand;

/**
 * Class DiceHandHandler.
 */
class DiceHandHandler //implements GameHandlerInterface
{

    private $dice;

    public function __construct(DiceHand $diceHand = null)
    {
        $this->diceHand = $diceHand;
        if ($diceHand === null) {
            $this->diceHand = new DiceHand();
        }
    }

    private function welcome()
    {
        return [
            'view' => 'dicehand',
            'message' => 'Please choose the amount of dice (1-10):',
            'roll' => null
        ];
    }

    private function roll($amount)
    {
        // add dice to dicehand
        for ($i = 0; $i < $amount; $i++) {
            $this->diceHand->addDice(new GraphicalDice());
        }
        // roll the dice
        $this->diceHand->roll();

        $result = "";
        // graphic representation of the dice
        for ($i = 0; $i < $amount; $i++) {
            $result .= "<span class=\"{$this->diceHand->graphicLastRoll()[$i]}\" ></span>";
        }

        return [
            'view' => 'dicehand',
            'message' => 'Please choose the amount of dice (1-10):',
            'roll' => 'You rolled: ' . $result
        ];
    }

    public function playGame(array $amount)
    {
        if (!isset($amount["dice"])) {
            return $this->welcome();
        }

        return $this->roll(intval($amount["dice"]));
    }
}
