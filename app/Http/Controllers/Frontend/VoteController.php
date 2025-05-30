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
            'votes' => 'required|array',
            'votes.*.seat_id' => 'required|exists:seats,id',
            'votes.*.candidate_id' => 'nullable|exists:candidates,id',
        ]);

        $electionId = Seat::where('id', $request->votes[0]['seat_id'])->value('election_id');
        $alreadyVoted = Vote::where('cnic', $request->cnic)
            ->where('election_id', $electionId)
            ->exists();

        if ($alreadyVoted) {
            return response()->json([
                'message' => 'This voter has already cast their vote in this election.'
            ], 409); // 409 Conflict
        }

        $votesData = [];
        foreach ($request->votes ?? [] as $vote) {
            $votesData[] = [
                'cnic' => $request->cnic,
                'election_id' => $electionId,
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
