<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Election;
use App\Http\Controllers\Controller;
use App\Vote;
use Illuminate\Support\Facades\Auth;
use Validator;

class ElectionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $elections = Election::with('createdBy')->get();
        return view('admin.elections.index', compact('elections'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.elections.create');
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
            'title_english' => 'required|string|max:255',
            'title_urdu' => 'nullable|string|max:255',
        ]);

        $electionData = [
            'title_english' => $request->input('title_english'),
            'title_urdu' => $request->input('title_urdu'),
            'created_by' => Auth::guard('admin')->user()->id,
        ];

        Election::create($electionData);

        return redirect()->route('elections.index')->with('success', 'Record Added Successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $election = Election::find($id);

        if ($election == null) {
            return redirect()->back()->with('error', 'No Record Found.');
        }

        $election->loadCount(['votes' => function ($query) {
            $query->whereNotNull('candidate_id');
        }]);

        $votes = $election->votes()
            ->whereNotNull('candidate_id')
            ->with(['seat:id,name_english,name_urdu', 'candidate:id,name_english,name_urdu'])
            ->get()
            ->groupBy('seat_id');

        return view('admin.elections.show', compact('election', 'votes'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $election = Election::find($id);

        if ($election == null) {
            return redirect()->back()->with('error', 'No Record Found.');
        }

        return view('admin.elections.edit', compact('election'));
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
        $election = Election::find($id);

        if ($election == null) {
            return redirect()->back()->with('error', 'No Record Found.');
        }

        $request->validate([
            'title_english' => 'required|string|max:255',
            'title_urdu' => 'nullable|string|max:255',
            'status' => 'required|in:0,1',
        ]);

        $electionData = [
            'title_english' => $request->input('title_english'),
            'title_urdu' => $request->input('title_urdu'),
            'status' => $request->input('status'),
        ];

        $election->update($electionData);

        return redirect()->route('elections.index')->with('success', 'Record Updated Successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    // public function destroy($id)
    // {
    //     $election = Election::find($id);

    //     if ($election == null) {
    //         return redirect()->back()->with('error', 'No Record Found.');
    //     }

    //     $election->delete();
    //     return redirect()->route('elections.index')->with('success', 'Record Deleted Successfully.');
    // }
}
