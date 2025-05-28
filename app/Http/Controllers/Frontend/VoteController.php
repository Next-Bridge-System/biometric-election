<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Seat;
use App\Vote;
use Illuminate\Http\Request;

class VoteController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'votes' => 'required|array',
            'votes.*.seat_id' => 'required|exists:seats,id',
            'votes.*.candidate_id' => 'required|exists:candidates,id',
        ]);

        $votesData = [];
        $electionId = Seat::where('id', $request->votes[0]['seat_id'])->first()->election_id;

        foreach ($request->votes as $vote) {
            $votesData[] = [
                'election_id' => $electionId,
                'seat_id' => $vote['seat_id'],
                'candidate_id' => $vote['candidate_id'],
            ];
        }

        Vote::insert($votesData);

        return response()->json(['message' => 'Votes submitted successfully']);
    }
}
