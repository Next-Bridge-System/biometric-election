<?php

namespace App\Http\Controllers\API;

use App\Application;
use App\Http\Controllers\API\BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class HighCourtController extends BaseController
{
    public function searchApplication(Request $request)
    {
        try {
            $cnic_no = $request->cnic_no;
            $license_no_hc = $request->license_no;
            $bf_no_hc = $request->bf_no;

            $validator = Validator::make($request->all(), [
                'cnic_no' => 'sometimes',
                'license_no' => 'sometimes',
                'bf_no' => 'sometimes',
            ]);

            if ($validator->fails()) {
                return $this->sendError($validator->errors());
            }

            $query = Application::select('*')
                ->whereIn('application_type', ['1', '2', '3', '4', '6'])
                ->where('advocates_name', '!=', NULL)
                ->where('cnic_no', '!=', NULL)
                ->where('active_mobile_no', '!=', NULL);

            if ($request->has('cnic_no')) {
                $query->where('cnic_no', $cnic_no);
            }

            if ($request->has('license_no')) {
                $query->where('license_no_hc', $license_no_hc);
            }

            if ($request->has('bf_no')) {
                $query->where('bf_no_hc', $bf_no_hc);
            }

            $application = $query->first();

            if ($application == NULL) {
                return $this->sendError('No record found');
            }

            $response['high_court'] = [
                'lawyer_name' => getLawyerName($application->id),
                'father_name' => $application->so_of,
                'cnic_no' => $application->cnic_no,
                'mobile_no' => '+92' . $application->active_mobile_no,
                'application_type' => getApplicationType($application->id),
            ];

            return $this->sendResponse($response, 'The record have been fetched successfully');
        } catch (\Throwable $th) {
            return $this->sendError('Operation failed to perform');
        }
    }
}
