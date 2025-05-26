<?php

namespace App\Http\Controllers;

use App\Application;
use App\PoliceVerification;
use Illuminate\Http\Request;

class PoliceVerificationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $application = Application::find($id);
        policeVerification($application);

        $result = PoliceVerification::where('application_id', $id)->first();

        return view('admin.intimations.police-verification', compact('application'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function changeStatus(Request $request)
    {
        if (!$request->has('application_id')) {
            return response()->json([
                'status' => 0,
                'message' => "System is busy, Please try again in a while"
            ]);
        }
        $id  = $request->application_id;

        $application = Application::find($id);

        if ($application == null || $application->fir == null) {
            return response()->json([
                'status' => 0,
                'message' => "No Record Found ..."
            ]);
        }

        $application->fir->update([
            'verified' => $request->status,
            'verified_by' => \Auth::guard('admin')->user()->id,
        ]);

        return response()->json([
            'status' => 1,
            'message' => "Status changed successfully",
        ]);
    }

    public function exportPDF($id)
    {
        $application = Application::find($id);

        if ($application == null || $application->fir == null) {
            return response()->json([
                'status' => 0,
                'message' => "No Record Found ..."
            ]);
        }

        return view('admin.intimations.police-verification-pdf', compact('application'));
        /*view()->share([
            'application' => $application
        ]);
        $pdf = \PDF::loadView('admin.intimations.police-verification-pdf');
        $pdf->setPaper('A4', 'landscape');
        return $pdf->stream('Certificate.pdf', array("Attachment" => false));*/
    }
}
