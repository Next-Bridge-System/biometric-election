<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Application;
use App\Biometric;
use App\User;
use Illuminate\Support\Facades\Validator;
use Str;
use PDF;

class BiometricController extends Controller
{
    public function store(Request $request)
    {
        // dd($request->all());

        $user = User::findOrFail($request->user_id);
        $biometric = Biometric::where('user_id', $user->id)->where('finger_id', $request->finger_id)->first();
        $biometric_count = Biometric::where('user_id', $user->id)->count();

        $data = [
            'user_id' => $user->id,
            'lawyer_name' => $user->name,
            'cnic_no' => $user->cnic_no,
            'finger_id' => $request->finger_id,
            'finger_name' => $request->finger_name,
            'template_1' => $request->template_1,
            'template_2' => $request->template_2,
            'fingerprint_response_1' => json_encode($request->result_1),
            'fingerprint_response_2' => json_encode($request->result_2),
            'created_by' => Auth::guard('admin')->user()->id,
            'updated_by' => Auth::guard('admin')->user()->id,
        ];

        if ($biometric) {
            $biometric->update($data);
        } else {
            $biometric = Biometric::create($data);
        }

        return response()->json([
            'success' => true,
            'message' => 'Biometric data saved successfully.',
            'finger_id' => $biometric->finger_id,
            'biometric_count' => $biometric_count
        ]);
    }

    public function registration($user_id)
    {
        $user = User::find($user_id);
        return view('admin.biometric.registration', compact('user'));
    }

    public function verification($user_id)
    {
        $user = User::find($user_id);
        if ($user->biometric_status == 0) {
            \abort('403', 'Biometric not registered for this user.');
        }

        $biometrics = Biometric::where('user_id', $user->id)->get();

        return view('admin.biometric.verification', compact('user', 'biometrics'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Biometric::find($id)->delete($id);

        return response()->json([
            'success' => 'Record deleted successfully!'
        ]);
    }

    public function pdf($id)
    {
        // $application = Application::find($id);
        // view()->share('application', $application);
        // $pdf = PDF::loadView('admin.biometric.pdf');
        // return $pdf->stream('BIOMETRIC-VERFICATION-' . $application->application_token_no . '.pdf');
        // return view('admin.biometric.pdf');
    }

    public function uploadCameraImage(Request $request)
    {
        $user = User::findOrFail($request->user_id);

        $data = $request->input('image');

        // Decode base64 image string
        if (preg_match('/^data:image\/(\w+);base64,/', $data, $type)) {
            $data = substr($data, strpos($data, ',') + 1);
            $type = strtolower($type[1]); // jpg, png, gif

            if (!in_array($type, ['jpg', 'jpeg', 'png'])) {
                return response()->json(['success' => false, 'message' => 'Invalid image type']);
            }

            $decodedImage = base64_decode($data);

            if ($decodedImage === false) {
                return response()->json(['success' => false, 'message' => 'Base64 decode failed']);
            }
        } else {
            return response()->json(['success' => false, 'message' => 'Invalid data URI']);
        }

        $imageName = $user->id . '-' . $user->clean_cnic_no   . '.png';
        $directory = 'biometrics/';
        $path = $directory . $imageName;

        Storage::disk('biometrics')->put($imageName, $decodedImage);

        $user->update([
            'webcam_image_path' => $path,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Webcam image saved successfully.',
        ]);
    }

    public function getBiometricCount(Request $request)
    {
        $user = User::findOrFail($request->user_id);
        $biometric_count = Biometric::where('user_id', $user->id)->count();

        if ($biometric_count >= 2) {
            $user->update(['biometric_status' => 1]); // Mark as Registered
        }

        return response()->json([
            'success' => true,
            'biometric_count' => $biometric_count,
        ]);
    }
}
