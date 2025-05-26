<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Application;
use App\LawyerCase;
use Validator;

class LawyerCaseController extends Controller
{
    public function create(Request $request)
    {
        if ($request->isMethod('post')) {

            if (strlen($request->rf_id) >= 10) {

                $application = Application::Where('rf_id', 'like', '%' . $request->rf_id . '%')->first();

                if (isset($application)) {
                    $getRfId = $application->rf_id;
                    $getName = $application->advocates_name .' '. $application->last_name;
                    $getCnic = $application->cnic_no;
                    $getProfileImage = asset('storage/app/public/'.$application->profile_image_url);
                }

                if ($application == NULL) {
                    return response()->json([
                        'errors' => 'NO RECORD FOUND.',
                    ], 400);
                }

                $request->session()->forget('application');
                if(empty($request->session()->get('application'))){
                    $application->fill([ 'application_id' => $application->id]);
                    $request->session()->put('application', $application->id);
                }

                $lawyerCases = LawyerCase::where('application_id', $application->id)->get();
                $listView= view('admin.cases._list',compact('lawyerCases'))->render();

                return response()->json([
                    'status' => 1,
                    'message' => 'success',
                    'getRfId' => $getRfId,
                    'getName' => $getName,
                    'getCnic' => $getCnic,
                    'getProfileImage' => $getProfileImage,
                    'listView' => $listView,
                ]);

            } else {
                return response()->json([
                    'errors' => 'Invalid RFID Number. Try Again',
                ], 400);
            }
        }

        return view('admin.cases.create');
    }

    public function store(Request $request)
    {
        $rules = [
            'case_title' => 'required|string|max:255',
            'case_type' => 'required|string|max:255',
            'judge_name' => 'required|string|max:255',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
            ], 400);
        }

        $applicationId = $request->session()->get('application');

        $data = [
            'application_id' => $applicationId,
            'case_title' => $request->input('case_title'),
            'case_type' => $request->input('case_type'),
            'judge_name' => $request->input('judge_name'),
        ];

        LawyerCase::create($data);

        $lawyerCases = LawyerCase::where('application_id', $applicationId)->get();
        $listView= view('admin.cases._list',compact('lawyerCases'))->render();

        return response()->json([
            'status' => 1,
            'message' => 'success',
            'listView' => $listView,
        ]);

    }
}
