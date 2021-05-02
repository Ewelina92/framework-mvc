<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Handlers\DiceHandHandler;
use Illuminate\Http\Request;

class DiceHandController extends Controller
{
    /**
     * Show the dicehand.
     *
     * @return \Illuminate\View\View
     */
    public function show(Request $request)
    {
        $diceHand = new DiceHandHandler();
        $data = $diceHand->playGame($request->all());

        return view($data["view"], $data);
    }
}
