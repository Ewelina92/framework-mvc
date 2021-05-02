<?php

namespace App\Models\DiceModels;

/**
 * Class Dice.
 */
class Dice
{
    private $faces;
    protected $roll;

    public function __construct(int $faces = 6, int $roll = null)
    {
        $this->faces = $faces;
        $this->roll = $roll;
    }

    public function roll(): int
    {
        $this->roll = rand(1, $this->faces);

        return $this->roll;
    }

    public function getLastRoll(): ?int
    {
        return $this->roll;
    }
}
