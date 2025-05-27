<?php

namespace App\Http\Controllers\Admin;

use App\Candidate;
use Illuminate\Http\Request;
use App\Election;
use App\Http\Controllers\Controller;
use App\Seat;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CandidateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $candidates = Candidate::with(['election', 'seat', 'createdBy'])->get();
        return view('admin.candidates.index', compact('candidates'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $elections = Election::all();
        return view('admin.candidates.create', compact('elections'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'election_id' => 'required|exists:elections,id',
            'seat_id' => 'required|exists:seats,id',
            'name_english' => 'required|string|max:255',
            'name_urdu' => 'nullable|string|max:255',
            'image' => 'nullable|mimes:jpg,jpeg,png|max:2048',
        ]);

        $imageUrl = null;
        $directory = 'elections/candidates';
        if ($request->hasFile('image')) {
            if (!Storage::exists($directory)) {
                Storage::makeDirectory($directory);
            }
            $imageUrl = Storage::putFile($directory, new File($request->file('image')));
        }

        $candidateData = [
            'election_id' => $request->input('election_id'),
            'seat_id' => $request->input('seat_id'),
            'name_english' => $request->input('name_english'),
            'name_urdu' => $request->input('name_urdu'),
            'image_url' => $imageUrl,
            'created_by' => Auth::guard('admin')->user()->id,
        ];

        Candidate::create($candidateData);

        return redirect()->route('candidates.index')->with('success', 'Record Added Successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $candidate = Candidate::find($id);
        $elections = Election::all();
        $seats = Seat::where('election_id', $candidate->election_id)->get();

        if ($candidate == null) {
            return redirect()->back()->with('error', 'No Record Found.');
        }

        return view('admin.candidates.edit', compact('candidate', 'seats', 'elections'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $candidate = Candidate::find($id);

        if ($candidate == null) {
            return redirect()->back()->with('error', 'No Record Found.');
        }

        $request->validate([
            'election_id' => 'required|exists:elections,id',
            'name_english' => 'required|string|max:255',
            'name_urdu' => 'nullable|string|max:255',
            'image' => 'nullable|image|max:2048',
        ]);

        $imageUrl = null;
        $directory = 'elections/candidates';
        if ($request->hasFile('image')) {
            if (!Storage::exists($directory)) {
                Storage::makeDirectory($directory);
            }
            $imageUrl = Storage::putFile($directory, new File($request->file('image')));

            if ($candidate->image_url) {
                Storage::delete($candidate->image_url);
            }
        }

        $candidateData = [
            'election_id' => $request->input('election_id'),
            'seat_id' => $request->input('seat_id'),
            'name_english' => $request->input('name_english'),
            'name_urdu' => $request->input('name_urdu'),
            'image_url' => $imageUrl,
        ];

        $candidate->update($candidateData);

        return redirect()->route('candidates.index')->with('success', 'Record Updated Successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $candidate = Candidate::find($id);

        if ($candidate == null) {
            return redirect()->back()->with('error', 'No Record Found.');
        }

        $candidate->delete();
        return redirect()->route('candidates.index')->with('success', 'Record Deleted Successfully.');
    }
}
