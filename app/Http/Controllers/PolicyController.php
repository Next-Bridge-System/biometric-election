<?php

namespace App\Http\Controllers;

use App\Policy;
use Illuminate\Http\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PolicyController extends Controller
{
    public function index(Request $request)
    {
        $policies = Policy::all();
        return view('admin.policies.index', compact('policies'));
    }

    public function create(Request $request)
    {
        return view('admin.policies.create');
    }

    public function store(Request $request)
    {

        $request->validate([
                'title' => 'required',
                'start_date' => 'required',
                'end_date' => 'required',
                'policy_url' => 'required|mimes:jpg,jpeg,png',
                'policy_fees' => 'required|array',
                'application_type' => 'required|in:1',
        ]);

        $startDate = \DateTime::createFromFormat('d-m-Y', $request->start_date);
        $endDate = \DateTime::createFromFormat('d-m-Y', $request->end_date);
        $exsistingPolicy = Policy::where('application_type',$request->application_type)
            ->whereDate('start_date','<=',$startDate)
            ->whereDate('end_date','>=',$startDate)
            ->get();

        if(strtotime($startDate->format('Y-m-d')) > strtotime($endDate->format('Y-m-d'))){
            $errors = [
                'start_date' => 'The End Date should be ahead of Start Date'
            ];
            return redirect()->back()->withErrors($errors);
        }elseif($exsistingPolicy->count() > 0){
            $errors = [
                'start_date' => 'There is exsiting policy within the timeline'
            ];
            return redirect()->back()->withErrors($errors);
        }

        $data = [
            'title' => $request->title,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'application_type' => $request->application_type,
        ];

        $policy = Policy::create($data);
        $this->uploadPolicyImage($request,$policy->id);

        if($request->has('policy_fees')){

            foreach ($request->policy_fees as $item){
                $ageGroup = explode('-',$item['age_group']);
                $policy->policyFees()->create([
                    'amount' => $item['amount'],
                    'from' => $ageGroup[0],
                    'to' => $ageGroup[1] ?? null,
                ]);
            }
        }

        return redirect()->route('policies.index')->with('success','Policy Added Successfully');
    }

    public function edit($id)
    {
        $policy = Policy::find($id);
        return view('admin.policies.edit',compact('policy'));
    }

    public function update(Request $request,$id)
    {
        $policy = Policy::find($id);
        $request->validate([
            'title' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
            'policy_url' => 'nullable|mimes:jpg,jpeg,png',
            'policy_fees' => 'required|array',
            'application_type' => 'required|in:1',
        ]);

        $startDate = \DateTime::createFromFormat('d-m-Y', $request->start_date);
        $endDate = \DateTime::createFromFormat('d-m-Y', $request->end_date);
        $exsistingPolicy = Policy::where('id','!=',$policy->id)
            ->where('application_type',$request->application_type)
            ->whereDate('start_date','<=',$startDate)
            ->whereDate('end_date','>=',$startDate)
            ->get();

        if(strtotime($startDate->format('Y-m-d')) > strtotime($endDate->format('Y-m-d'))){
            $errors = [
                'start_date' => 'The End Date should be ahead of Start Date'
            ];
            return redirect()->back()->withErrors($errors);
        }elseif($exsistingPolicy->count() > 0){
            $errors = [
                'start_date' => 'There is exsiting policy within the timeline'
            ];
            return redirect()->back()->withErrors($errors);
        }

        $data = [
            'title' => $request->title,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'application_type' => $request->application_type,
        ];

        $policy->update($data);
        if($request->has('policy_url') && $policy->policy_url != NULL){
            $this->uploadPolicyImage($request,$policy->id);
        }

        if($request->has('policy_fees')){
            $policy->policyFees()->delete();
            foreach ($request->policy_fees as $item){
                $ageGroup = explode('-',$item['age_group']);
                $policy->policyFees()->create([
                    'amount' => $item['amount'],
                    'from' => $ageGroup[0],
                    'to' => $ageGroup[1] ?? null,
                ]);
            }
        }

        return redirect()->route('policies.index')->with('success','Policy Edited Successfully');
    }


    public function show(Request $request)
    {

    }

    public function uploadPolicyImage(Request $request, $id)
    {
        $model = Policy::find($id);
        $directory = 'policies/'.$model->id;
        if ($request->hasFile('policy_url')) {
            $fileName = $request->file('policy_url')->getClientOriginalName();
            if(!Storage::exists($directory)){
                Storage::makeDirectory($directory);
            }
            $url = Storage::putFile($directory, new File($request->file('policy_url')));
            $model->update(['policy_url'=> $url]);
        }
    }
}
