<?php

namespace App\Http\Controllers;

use App\Bar;
use Illuminate\Http\Request;
use Auth;
use Hash;
use Illuminate\Validation\Rule;
use Validator;
use App\Admin;
use App\Permission;
use OwenIt\Auditing\Models\Audit;

class AdminUserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $adminUsers = Admin::where('id', '!=', Auth::guard('admin')->user()->id)->where('id', '!=', '1')->get();
        $adminUsers = Admin::get();
        return view('admin.adminusers.index', compact('adminUsers'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $permissions = Permission::orderBy('order', 'asc')->orderBy('type', 'asc')->get();
        $barAssociates = Bar::orderBy('name', 'asc')->get();
        return view('admin.adminusers.create', compact('permissions', 'barAssociates'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $validatedData = $request->validate([
            'name' => 'required|string|max:50',
            'email' => 'required|string|email|max:50|unique:admins',
            'phone' => 'required|numeric|digits_between:10,15',
            'password' => 'required|string|min:6|confirmed|max:32',
            'is_super' => 'required|in:1,0',
            'bar_id' => [Rule::requiredIf($request->input('is_super') == 0)],
            'permissions' => 'required',
        ]);

        $adminUserData = [
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'phone' => $request->input('phone'),
            'is_super' => $request->input('is_super'),
            'bar_id' => $request->has('bar_id') ? $request->input('bar_id') : NULL,
            'password' => Hash::make($request->input('password')),
        ];

        $adminUser = Admin::create($adminUserData);

        if ($request->has('permissions')) {
            $permissions = Permission::whereIn('id', $request->permissions)->get();
            $adminUser->permissions()->attach($permissions);
        }

        return redirect()->route('admins.index')->with('success', 'Record Added Successfully.');
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
        $admin = Admin::find($id);
        if ($admin == null) {
            return redirect()->back()->with('error', 'No Record Found.');
        }
        $permissions = Permission::orderBy('order', 'asc')->orderBy('type', 'asc')->get();
        $barAssociates = Bar::orderBy('name', 'asc')->get();
        return view('admin.adminusers.edit', compact('admin', 'permissions', 'barAssociates'));
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
        $admin = Admin::find($id);
        if ($admin == null) {
            return redirect()->back()->with('error', 'No Record Found.');
        }

        $rules = [
            'name' => 'required|string|max:150',
            'email' => 'required|email|unique:admins,email,' . $admin->id,
            'phone' => 'required',
            'is_super' => 'required|in:1,0',
            'bar_id' => [Rule::requiredIf($request->input('is_super') == 0)],
            'permissions' => 'required|array'
        ];

        if (!empty($request->password) || !empty($request->password_confirmation)) {
            $rules['password'] = 'required|string|min:6|max:32|confirmed';
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return Redirect()->back()->withErrors($validator)->withInput($request->all());
        }

        $userData = [
            'name' => $request->input('name'),
            'phone' => $request->input('phone'),
            'is_super' => $request->input('is_super'),
            'bar_id' => $request->has('bar_id') ? $request->input('bar_id') : NULL,
            'status' => $request->input('status'),
        ];

        if (!empty($request->password)) {
            $userData['password'] = Hash::make($request->password);
        }

        if ($request->has('permissions')) {
            $admin->permissions()->detach();
            $permissions = Permission::whereIn('id', $request->permissions)->get();
            $admin->permissions()->attach($permissions);
        }
        $admin->update($userData);

        return redirect()->route('admins.index')->with('success', 'Record Updated Successfully.');
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

    public function audit(Request $request, $id)
    {
        // dd($request->all());

        $admin = Admin::findOrFail($id);

        $audits = Audit::where('user_id', $id)
            ->when($request->has('date'), function ($query) use ($request) {
                $query->whereDate('created_at', $request->date);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        if ($request->ajax()) {
            return response()->json([
                'status' => 'success',
                'data' => $audits
            ]);
        }

        return view('admin.audits.index', compact('audits', 'admin'));
    }
}
