<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Application;
use App\LowerCourt;
use App\User;

class SendMessageController extends Controller
{
    public function sendMessage(Request $request)
    {
        $key = $request->send_message_value;

        if ($request->application_type == 'intimation') {
            $application = Application::find($request->application_id);
            $application_no = $application->application_token_no;
            $application_type = 'Intimation';
        }

        if ($request->application_type == 'lc') {
            $application = LowerCourt::find($request->application_id);
            $application_no = $application->id;
            $application_type = 'Lower Court';
        }

        $user = User::find($application->user_id);

        switch ($key) {

            case $key == 1:
                $data = [
                    "phone" => '+92' . $user->phone,
                    "message" => 'Dear Applicant, Your account has been register successfully. Email: ' . $user->email,
                ];
                sendMessageAPI($data);
                break;

            case $key == 2:
                $otp = isset($user->otp) ? $user->otp : '******';
                $data = [
                    "phone" => '+92' . $user->phone,
                    "message" => 'Dear Applicant, Your OTP is ' . $otp,
                ];
                sendMessageAPI($data);
                break;

            case $key == 3:
                $data = [
                    "phone" => '+92' . $user->phone,
                    "message" => 'Dear Applicant, Your ' . $application_type . ' application has been submitted. Application No: ' . $application_no,
                ];
                sendMessageAPI($data);
                break;

            case $key == 4:
                $application_assign_members = $application->assignMembers;
                foreach ($application_assign_members as $key => $application_assign_member) {
                    $canidate_sms = [
                        "event_id" => 195,
                        "phone" => '+92' . $user->phone,
                        "member_name" => $application_assign_member->member->name,
                        "member_contact" => '0' . $application_assign_member->member->mobile_no,
                    ];
                    sendMessageAPI($canidate_sms);
                }
                foreach ($application_assign_members as $key => $application_assign_member) {
                    $member_sms = [
                        "event_id" => 193,
                        "phone" => '+92' . $application_assign_member->member->mobile_no,
                        "candidate_cnic" => $application->cnic_no,
                        "candidate_code" => $application_assign_member->code,
                    ];
                    sendMessageAPI($member_sms);
                }

                break;

            default:
                break;
        }
    }
}
