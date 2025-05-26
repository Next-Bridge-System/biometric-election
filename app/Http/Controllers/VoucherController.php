<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Voucher;

class VoucherController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Voucher::select('*');
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('application_type', function ($data) {
                    if ($data->application_type == 1) $type = 'Lower Court';
                    else if ($data->application_type == 2) $type = 'High Court';
                    return $type;
                })
                ->addColumn('station', function ($data) {
                    return $data->bar->name;
                })
                ->addColumn('action', function ($data) {
                    $btn = '<a href="' . route('vouchers.show', $data->id) . '">
                    <span class="badge badge-primary"><i class="fas fa-eye mr-1" aria-hidden="true"></i>View</span></a>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('admin.vouchers.index');
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
        $voucher = Voucher::findOrFail($id);
        return view('admin.vouchers.show', compact('voucher'));
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

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function payment(Request $request)
    {
        $voucher = Voucher::find($request->voucher_id);
        $voucher->update(['payment_status' => $request->payment_status]);
        foreach ($voucher->payments as $key => $payment) {
            if ($voucher->payment_status == 'PAID') {
                $payment->update(['vch_payment_status' => TRUE]);
            } else {
                $payment->update(['vch_payment_status' => FALSE]);
            }
        }

        return redirect()->back();
    }
}
