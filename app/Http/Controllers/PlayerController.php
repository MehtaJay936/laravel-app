<?php

namespace App\Http\Controllers;

use App\Http\Requests\playerStoreRequest;
use App\Models\Player;
use App\Models\Team;
use App\Models\TeamPlayer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PlayerController extends Controller
{
    public function index()
    {
        $players = Player::all();

        return response()->json([
            'players' => $players,
        ]);
    }

    public function store(playerStoreRequest $request)
    {
        info($request);

        $player = Player::create([
            'name' => $request->name,
            'email' => $request->email,
            'date_of_birth' => $request->date_of_birth,
        ]);

        return response()->json(['data' => $player, 'message' => 'Saved successfully']);
    }
}
