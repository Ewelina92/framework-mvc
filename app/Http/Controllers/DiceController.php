<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Handlers\DiceHandler;
use Illuminate\Http\Request;

class DiceController extends Controller
{
    /**
     * Display a dice.
     *
     * @return \Illuminate\View\View
     */
    public function show(Request $request)
    {
        $diceHandler = new DiceHandler();
        $data = $diceHandler->playGame($request->all());

        return view($data["view"], $data);
    }
}
