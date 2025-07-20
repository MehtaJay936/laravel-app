<?php

namespace App\Http\Controllers;

use App\Http\Requests\playerStoreRequest;
use App\Models\Player;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PlayerController extends Controller
{
    public function index()
    {
        try {
            $players = Player::all();

            return response()->json([
                'players' => $players,
            ]);
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to retrieve players'], 500);
        }
    }

    public function store(playerStoreRequest $request)
    {
        try {
            $player = Player::create([
                'name' => $request->name,
                'email' => $request->email,
                'date_of_birth' => $request->date_of_birth,
            ]);

            return response()->json([
                'data' => $player,
                'message' => 'Player Saved successfully'
            ]);
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to create player'], 500);
        }
    }

    public function edit(Player $player)
    {
        try {
            return response()->json($player);
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to retrieve player'], 500);
        }
    }

    public function update(playerStoreRequest $request, Player $player)
    {
        try {
            $player->update([
                'name' => $request->name,
                'email' => $request->email,
                'date_of_birth' => $request->date_of_birth,
            ]);

            return response()->json(['data' => $player, 'message' => 'Player Updated successfully']);
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to update player'], 500);
        }
    }

    public function destroy(Player $player)
    {
        try {
            $player->delete();

            return response()->json(['message' => 'Player deleted successfully']);
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to delete player'], 500);
        }
    }
}