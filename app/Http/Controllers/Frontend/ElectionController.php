<?php

namespace App\Http\Controllers\Frontend;

use App\Biometric;
use App\Candidate;
use App\Election;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;

class ElectionController extends Controller
{
    public function index()
    {        
        $candidateData = Candidate::select(
            'candidates.id as candidate_id',
            'candidates.name_english as candidate_name_eng',
            'candidates.name_urdu as candidate_name_urdu',
            'seats.id as seat_id',
            'seats.name_english as seat_name_english',
            'seats.name_urdu as seat_name_urdu'
        )
            ->join('elections', 'candidates.election_id', '=', 'elections.id')
            ->join('seats', 'candidates.seat_id', '=', 'seats.id')
            ->where('elections.status', 1)
            ->where('seats.status', 1)
            ->where('candidates.status', 1)
            ->get();

        $finalData = [];

        // Group data by seat_id using foreach
        foreach ($candidateData as $candidate) {
            $seatId = $candidate->seat_id;

            // If the seat is not already added, initialize it
            if (!isset($finalData[$seatId])) {
                $finalData[$seatId] = [
                    'id' => $candidate->seat_id,
                    'category' => $candidate->seat_name_english,
                    'urdu' => $candidate->seat_name_urdu,
                    'candidates' => []
                ];
            }

            // Add candidate to the seat's candidates list
            $finalData[$seatId]['candidates'][] = [
                'id' => $candidate->candidate_id,
                'name' => $candidate->candidate_name_eng,
                'image' => $candidate->candidate_name_urdu,
            ];
        }

        // Re-index the array to get a clean list
        $final_candidates = array_values($finalData);

        return view('frontend.election.index', compact('final_candidates'));
    }

    public function fetchSavedFingerTemplates(Request $request)
    {

        $cleanCnic = str_replace('-', '', $request->cnic_no);
        $election = Election::where('status', 1)->first();
        $alreadyVoted = $election->votes()->where('cnic', $cleanCnic)->exists();

        if ($alreadyVoted) {
            return response()->json([
                'message' => 'This voter has already cast their vote in this election.'
            ], 409); // 409 Conflict
        }

        $biometrics = Biometric::select('id', 'user_id', 'finger_id', 'finger_name', 'template_2')
            ->whereRaw('REPLACE(cnic_no, "-", "") = ?', [$cleanCnic])->get();

        if ($biometrics->count() >= 2) {
            return response()->json($biometrics);
        }

        return response()->json(null, 404);
    }

    public function fetchVerifiedVoterData(Request $request)
    {
        $cleanCnic = str_replace('-', '', $request->cnic_no);
        $user = User::where('clean_cnic_no', $cleanCnic)->first();

        return response()->json($user);
    }

    public function submitVote(Request $request)
    {
        dd($request->all());
    }
}
