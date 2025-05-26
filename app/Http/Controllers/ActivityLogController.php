<?php

namespace App\Http\Controllers;

use App\ActivityLog;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ActivityLogController extends Controller
{
    public function __construct()
    {
        $this->admin = Auth::guard('admin')->user();
    }

    public function index($id)
    {
        $user = User::find($id);

        if($user == NULL){
            return redirect()->back()->with('error','No Record Found');
        }
        $response['user'] = $user;
        $dates = ActivityLog::select('activity_at')->where('user_id',$user->id)->orderBy('activity_at','DESC')->pluck('activity_at')->toArray();
        $uniqueDates = array_unique($dates);
        $response['dates'] = $uniqueDates;
        $response['paymentActivities'] = ActivityLog::where('user_id',$user->id)->where('type','Payment')->get();
        $response['applicationActivities'] = ActivityLog::where('user_id',$user->id)->where('type','Application')->get();
        return view('admin.activity-logs.index')->with($response);

    }

    public function lowerCourtIndex($id)
    {
        $user = User::find($id);

        if($user == NULL){
            return redirect()->back()->with('error','No Record Found');
        }
        $response['user'] = $user;
        $dates = ActivityLog::select('activity_at')->where('user_id',$user->id)->orderBy('activity_at','DESC')->pluck('activity_at')->toArray();
        $uniqueDates = array_unique($dates);
        $response['dates'] = $uniqueDates;
        $response['paymentActivities'] = ActivityLog::where('user_id',$user->id)->where('type','LC-Payment')->get();
        $response['applicationActivities'] = ActivityLog::where('user_id',$user->id)->where('type','LowerCourt')->get();
        return view('admin.lower-court.activity-log')->with($response);

    }

}
