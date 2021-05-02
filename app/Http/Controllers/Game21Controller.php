<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Handlers\Game21Handler;
use Illuminate\Http\Request;

class Game21Controller extends Controller
{
    /**
     * Display a dice.
     *
     * @return \Illuminate\View\View
     */
    public function show(Request $request)
    {
        $game21Handler = new Game21Handler();
        $data = $game21Handler->playGame();

        return view($data["pageToRender"], $data);
    }
}
