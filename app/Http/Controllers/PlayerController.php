<?php

namespace App\Http\Controllers;

use App\Models\Player;
use App\Models\Team;
use App\Models\TeamPlayer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PlayerController extends Controller
{
    public function index()
    {
        $assignedIds = TeamPlayer::pluck('player_id');
        $unassignedPlayers = Player::whereNotIn('id', $assignedIds)->get();
        $teams = Team::with('players')->get();

        return response()->json([
            'unassignedPlayers' => $unassignedPlayers,
            'teams' => $teams,
        ]);
    }

    public function store(Request $request)
    {
        TeamPlayer::truncate();

        foreach ($request->teams as $team) {
            foreach ($team['players'] as $player) {
                TeamPlayer::insert([
                    'team_id'    => $team['team_id'],
                    'player_id'  => $player['player_id'],
                    'sort_order' => $player['sort_order'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        return response()->json(['message' => 'Saved successfully']);
    }
}
