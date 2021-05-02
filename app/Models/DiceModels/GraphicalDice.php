<?php

declare(strict_types=1);

namespace App\Models\DiceModels;

/**
 * Class GraphicalDice.
 */
class GraphicalDice extends Dice
{
    /**
     * @var integer SIDES Number of sides of the Dice.
     */
    private const SIDES = 6;

    /**
     * Constructor to initiate the dice with six number of sides.
     */
    public function __construct(int $roll = null)
    {
        parent::__construct(self::SIDES, $roll);
    }

    public function graphic(): string
    {
        return "die die-" . $this->roll;
    }
}
