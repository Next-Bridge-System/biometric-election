<?php

namespace App\Http\Controllers\Frontend;

use App\Vote;
use App\Election;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;

class VoteController extends Controller
{
    public function store(Request $request)
    {
        // dd($request->all());

        $user = User::whereRaw('REPLACE(cnic_no, "-", "") = ?', [str_replace('-', '', $request->cnic)])->first();
        printVoteConfirmationReceipt($user, $request->votes);

        $request->validate([
            'cnic' => 'required|string|max:15',
            'votes' => 'required|array',
            'votes.*.seat_id' => 'required|exists:seats,id',
            'votes.*.candidate_id' => 'nullable|exists:candidates,id',
        ]);

        $electionId = Election::where('status', 1)->value('id');
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
                'seat_id' => $vote['seat_id'],
                'candidate_id' => data_get($vote, 'candidate_id'),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        Vote::insert($votesData);

        return response()->json(['message' => 'Votes submitted successfully']);
    }
}
