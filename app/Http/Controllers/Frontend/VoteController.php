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
            'cnic' => 'required|string|max:15',
            'votes' => 'nullable|array',
            'votes.*.seat_id' => 'nullable|exists:seats,id',
            'votes.*.candidate_id' => 'nullable|exists:candidates,id',
        ]);

        $votesData = [];
        $seatId = isset($request->votes[0]['seat_id']) ? $request->votes[0]['seat_id'] : null;
        $seat = null;

        if ($seatId) {
            $seat = Seat::where('id', $seatId)->first();
        }

        foreach ($request->votes ?? [] as $vote) {
            $votesData[] = [
                'cnic' => $request->cnic,
                'election_id' => $seat ? $seat->election_id : $seat,
                'seat_id' => data_get($vote, 'seat_id'),
                'candidate_id' => data_get($vote, 'candidate_id'),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        Vote::insert($votesData);

        return response()->json(['message' => 'Votes submitted successfully']);
    }
}
