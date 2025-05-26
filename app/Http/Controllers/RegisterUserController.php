<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Validator;
use DataTables;
use Illuminate\Validation\Rule;


class RegisterUserController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = User::query();
            $query->select('id', 'name', 'email', 'phone', 'cnic_no', 'otp', 'phone_verified_at', 'register_as', 'is_excel');

            if ($request->search['value']) {
                $query->orderBy('id', 'asc');
            } else {
                $query->orderBy('id', 'desc');
            }

            return Datatables::of($query)
                ->addIndexColumn()
                ->addColumn('phone', function ($row) {
                    $phone = null;
                    if ($row->phone) {
                        $phone = "+92" . $row->phone;
                    }
                    return $phone;
                })
                ->addColumn('action', function ($row) {
                    $btn = null;

                    if (auth()->guard('admin')->user()->hasPermission('edit-users')) {
                        $btn .= '<a href="' . route('users.edit', ['id' => $row->id]) . '" class="edit btn btn-primary btn-xs mr-1"><i class="fas fa-edit mr-1"></i>Edit</a>';
                    }

                    if (auth()->guard('admin')->user()->hasPermission('users-direct-login') && $row->is_excel == 0) {
                        $btn .= '<a href="' . route('frontend.dashboard', ['id' => $row->id, 'direct_login' => TRUE]) . '" class="edit btn btn-success btn-xs mr-1" target="_blank"><i class="fas fa-sign-in-alt mr-1"></i>Direct Login</a>';
                    }

                    $btn .= '<a href="' . route('users.audit', $row->id) . '" class="edit btn btn-primary btn-xs mr-1"><i class="fas fa-folder mr-1"></i>Audit</a>';

                    return $btn;
                })
                ->setRowClass(function ($row) {
                    if ($row->is_excel == 1) {
                        $class = 'bg-light-red';
                    } else {
                        $class = '';
                    }
                    return $class;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('admin.users.index');
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        if (in_array($user->register_as, ['gc_lc', 'gc_hc'])) {
            abort(403);
        }

        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request)
    {
        $user = User::find($request->user_id);

        $rules = [
            'name' => ['required', 'string', 'max:255'],
            // 'lname' => ['required', 'string', 'max:255'],
            // 'lname' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id, 'id')],
            'phone' => ['required', Rule::unique('users')->ignore($user->id, 'id'), 'regex:/(3)[0-9]{9}/'],
            'cnic_no' => 'required|min:15|unique:users,cnic_no,' . $user->id,
        ];


        if (auth()->guard('admin')->user()->id == 1) {
            $rules += [
                'register_as' => 'required|in:intimation,lc,hc',
            ];
        }


        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
            ], 400);
        }

        // $name = $request->fname . ' ' . $request->lname;

        $data = [
            'name' => $request->name,
            // 'fname' => $request->fname,
            // 'lname' => $request->lname,
            'email' => $request->email,
            'phone' => $request->phone,
            'cnic_no' => $request->cnic_no,
        ];

        if (auth()->guard('admin')->user()->id == 1) {
            $data += [
                'register_as' => $request->register_as,
            ];
        }

        $user->update($data);

        $resposne = [];
        $response['status'] = 1;
        $response['message'] = 'Success';

        return response()->json($response, 200);
    }

    public function status(Request $request)
    {

        $user = User::find($request->row_id);
        $user->update([
            'status' => $request->status,
        ]);

        $resposne = [];
        $response['status'] = 1;
        $response['message'] = 'Success';

        return response()->json($response, 200);
    }

    public function audit($id)
    {
        $application = User::find($id);
        $audits = $application->audits()->paginate(10);
        return view('admin.audits.index', compact('audits'));
    }

    public function show($id)
    {
        $user = User::findOrFail($id);
        return view('admin.users.show', compact('user'));
    }
}
