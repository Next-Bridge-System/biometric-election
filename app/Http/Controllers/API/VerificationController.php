<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use App\Application;

class VerificationController extends Controller
{
    public function lawyer(Request $request)
    {
        $application = Application::select('*')->where('cnic_no', $request->cnic)->first();

        $data = [];

        if (isset($application) && $application != NULL) {
            $data = [
                'lawyer_name' => $application->advocates_name.' '.$application->last_name,
                'father_name' => $application->so_of,
                'cnic' => $application->cnic_no,
                'mobile' => $application->active_mobile_no,
                'date_of_birth' => $application->date_of_birth,
            ];

            return response()->json([
                'status' => 200,
                'message' => 'success',
                'data' => $data,
            ]);
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'No Record Found',
                'data' => $data,
            ]);
        }

    }
}
