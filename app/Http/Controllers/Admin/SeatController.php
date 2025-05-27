<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Election;
use App\Http\Controllers\Controller;
use App\Seat;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Validator;

class SeatController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $seats = Seat::with(['election', 'createdBy'])->get();
        return view('admin.seats.index', compact('seats'));
    }

    public function getByElection(Request $request)
    {
        $request->validate([
            'election_id' => 'required|exists:elections,id',
        ]);

        $seats = Seat::where('election_id', $request->election_id)->where('status', 1)->get();
        return ['seats' => $seats];
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $elections = Election::where('status', 1)->get();
        return view('admin.seats.create', compact('elections'));
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
            'name_english' => 'required|string|max:255',
            'name_urdu' => 'nullable|string|max:255',
            'image' => 'nullable|mimes:jpg,jpeg,png|max:2048',
        ]);

        // dd($request->hasFile('image'));

        $imageUrl = null;
        $directory = 'elections/seats';
        if ($request->hasFile('image')) {
            if (!Storage::exists($directory)) {
                Storage::makeDirectory($directory);
            }
            $imageUrl = Storage::putFile($directory, new File($request->file('image')));
        }

        $seatData = [
            'election_id' => $request->input('election_id'),
            'name_english' => $request->input('name_english'),
            'name_urdu' => $request->input('name_urdu'),
            'image_url' => $imageUrl,
            'created_by' => Auth::guard('admin')->user()->id,
        ];

        Seat::create($seatData);

        return redirect()->route('seats.index')->with('success', 'Record Added Successfully.');
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
        $seat = Seat::find($id);

        if ($seat == null) {
            return redirect()->back()->with('error', 'No Record Found.');
        }

        $elections = Election::where('status', 1)->get();

        return view('admin.seats.edit', compact('seat', 'elections'));
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
        $seat = Seat::find($id);

        if ($seat == null) {
            return redirect()->back()->with('error', 'No Record Found.');
        }

        $request->validate([
            'election_id' => 'required|exists:elections,id',
            'name_english' => 'required|string|max:255',
            'name_urdu' => 'nullable|string|max:255',
            'image' => 'nullable|image|max:2048',
            'status' => 'required|in:0,1',
        ]);

        $seatData = [];
        $directory = 'elections/seats';
        if ($request->hasFile('image')) {
            if (!Storage::exists($directory)) {
                Storage::makeDirectory($directory);
            }
            $imageUrl = Storage::putFile($directory, new File($request->file('image')));

            if ($seat->image_url) {
                Storage::delete($seat->image_url);
            }

            $seatData['image_url'] = $imageUrl;
        }

        $seatData = array_merge($seatData, [
            'election_id' => $request->input('election_id'),
            'name_english' => $request->input('name_english'),
            'name_urdu' => $request->input('name_urdu'),
            'status' => $request->input('status'),
        ]);

        $seat->update($seatData);

        return redirect()->route('seats.index')->with('success', 'Record Updated Successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    // public function destroy($id)
    // {
    //     $seat = Seat::find($id);

    //     if ($seat == null) {
    //         return redirect()->back()->with('error', 'No Record Found.');
    //     }

    //     $seat->delete();
    //     return redirect()->route('seats.index')->with('success', 'Record Deleted Successfully.');
    // }
}
