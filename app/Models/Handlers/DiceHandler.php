<?php

declare(strict_types=1);

namespace App\Models\Handlers;

use App\Models\DiceModels\GraphicalDice;

/**
 * Class DiceHandler.
 */
class DiceHandler //implements GameHandlerInterface
{

    private $dice;

    public function __construct(GraphicalDice $dice = null)
    {
        $this->dice = $dice;
        if ($dice === null) {
            $this->dice = new GraphicalDice();
        }
    }

    private function welcome()
    {
        return [
            'view' => 'dice',
            'message' => 'Please roll the dice',
            'roll' => null
        ];
    }

    private function roll()
    {
        $this->dice->roll();
        $result = $this->dice->graphic();


        return [
            'view' => 'dice',
            'message' => 'Please roll the die.',
            'roll' => $result
        ];
    }

    public function playGame(array $roll)
    {
        if (!isset($roll["dieRoll"])) {
            return $this->welcome();
        }

        return $this->roll();
    }
}
