<?php

//declare(strict_types=1);

namespace App\Models\DiceModels;

/**
 * Class DiceHand.
 */
class DiceHand
{
    private $dices;
    private $values;
    private int $sum;
    private $graphicClasses;

    public function __construct()
    {
        $this->dices = [];
        $this->graphicClasses = [];
        $this->values = [];
        $this->sum = 0;
    }

    public function roll(): void
    {
        $len = count($this->dices);
        $this->sum = 0;

        for ($i = 0; $i < $len; $i++) {
            $value = $this->dices[$i]->roll();
            $this->values[$i] = $value;

            $this->sum += $value;
        }
    }

    public function addDice(Dice $dice): void
    {
        array_push($this->dices, $dice);
        $value = end($this->dices)->getLastRoll();
        if ($value !== null) {
            array_push($this->values, $value);
            $this->sum += $value;
        }
    }

    public function getSum(): int
    {
        return $this->sum;
    }

    public function getDiceValues(): array
    {

        return $this->values;
    }

    public function getLastRoll(): string
    {
        $len = count($this->dices);

        $res = "";
        for ($i = 0; $i < $len; $i++) {
            $res .= $this->dices[$i]->getLastRoll() . ", ";
        }

        return $res . " = " . $this->sum;
    }

    public function graphicLastRoll(): array
    {
        $len = count($this->dices);

        for ($i = 0; $i < $len; $i++) {
            $this->graphicClasses[$i] = $this->dices[$i]->graphic();
        }

        return $this->graphicClasses;
    }
}
