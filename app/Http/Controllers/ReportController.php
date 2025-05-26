<?php

namespace App\Http\Controllers;

use App\Admin;
use App\District;
use App\Division;
use App\Exports\GeneralReportExport;
use App\Exports\IntimationReportExport;
use App\Exports\VPPReportExport;
use App\Tehsil;
use App\VPP;
use App\Application;
use App\AppStatus;
use App\Bar;
use App\University;
use App\GcHighCourt;
use App\GcLowerCourt;
use App\HighCourt;
use App\Jobs\GeneratePdfJob;
use App\LowerCourt;
use App\Payment;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    // **********************************************
    // ************ GENERAL REPORT ******************
    // **********************************************

    public function index(Request $request)
    {
        $admin = Auth::guard('admin')->user();
        $operators = Admin::select('id', 'name')->get();
        $bars = Bar::all();
        $divisions = Division::all();

        if ($request->ajax()) {
            $application = Application::select('*')->whereIn('application_type', [1, 2, 3, 4]);
            $admin = Auth::guard('admin')->user();

            return Datatables::of($application)
                ->addIndexColumn()
                ->addColumn('application_type', function (Application $application) {
                    return getApplicationType($application->id);
                })
                ->addColumn('active_mobile_no', function (Application $application) {
                    $activeMobileNumber = "+92" . $application->active_mobile_no;
                    return $activeMobileNumber;
                })
                ->addColumn('application_status', function (Application $application) {
                    return getApplicationStatus($application->id);
                })
                ->addColumn('card_status', function (Application $application) {
                    return getCardStatus($application->id);
                })
                ->addColumn('address', function (Application $application) {
                    $address = $application->postal_address . ' ' . $application->address_2;
                    return $address;
                })
                ->addColumn('submitted_by', function (Application $application) {
                    if (isset($application->submitted_by)) {
                        $submittedBy = getAdminName($application->submitted_by);
                    } else {
                        $submittedBy = "Online User";
                    }
                    return $submittedBy;
                })
                ->filter(function ($instance) use ($request) {

                    if ($request->get('application_type')) {
                        $instance->where('application_type', $request->get('application_type'));
                    }

                    if (!empty($request->get('card_status'))) {
                        $instance->where('card_status', $request->get('card_status'));
                    }

                    if (!empty($request->get('application_status'))) {
                        $instance->where('application_status', $request->get('application_status'));
                    }

                    if ($request->get('payment_status')) {
                        if ($request->get('payment_status') == 'paid') {
                            $payments = Payment::where('payment_status', 1)->pluck('application_id')->toArray();
                            $instance->whereIn('id', $payments);
                        }
                        if ($request->get('payment_status') == 'unpaid') {
                            $payments = Payment::where('payment_status', 0)->pluck('application_id')->toArray();
                            $instance->whereIn('id', $payments);
                        }
                    }

                    if ($request->has('bar_id') && !empty($request->get('bar_id'))) {
                        $instance->where('voter_member_lc', $request->get('bar_id'))
                            ->orWhere('voter_member_hc', $request->get('bar_id'));
                    }

                    if ($request->has('division_id') && !empty($request->get('division_id'))) {
                        $districtIDs = District::where('division_id', $request->division_id)->pluck('id')->toArray();
                        $tehsilIDs = Tehsil::select('id')->whereIn('district_id', $districtIDs)->pluck('id')->toArray();
                        $barIDs = Bar::select('id')->whereIn('tehsil_id', $tehsilIDs)->pluck('id')->toArray();
                        $instance->whereIn('voter_member_lc', $barIDs)->orWhereIn('voter_member_hc', $barIDs);
                    }

                    if ($request->get('application_date')) {
                        if ($request->get('application_date') == '1') {
                            $instance->whereDate('created_at', Carbon::today());
                        }
                        if ($request->get('application_date') == '2') {
                            $instance->whereDate('created_at', Carbon::yesterday());
                        }
                        if ($request->get('application_date') == '3') {
                            $date = Carbon::now()->subDays(7);
                            $instance->where('created_at', '>=', $date);
                        }
                        if ($request->get('application_date') == '4') {
                            $date = Carbon::now()->subDays(30);
                            $instance->where('created_at', '>=', $date);
                        }
                        if ($request->application_date == 5) {
                            if ($request->get('application_custom_date')) {
                                $date = date("Y-m-d", strtotime($request->get('application_custom_date')));
                                $instance->whereDate('created_at', $date);
                            }
                        }
                        if ($request->application_date == 6) {
                            if ($request->get('application_date_range')) {
                                $dateRange = explode(' - ', $request->application_date_range);
                                $from = date("Y-m-d", strtotime($dateRange[0]));
                                $to = date("Y-m-d", strtotime($dateRange[1]));
                                $instance->whereBetween('created_at', [$from, $to]);
                            }
                        }
                    }

                    if ($request->get('application_operator')) {
                        $instance->where('submitted_by', $request->application_operator);
                    }

                    if (!empty($request->get('search'))) {
                        $instance->where(function ($query) use ($request) {
                            $search = $request->get('search');
                            $query->orWhere('advocates_name', 'LIKE', "%$search%")
                                ->orWhere('cnic_no', 'LIKE', "%$search%")
                                ->orWhere('active_mobile_no', 'LIKE', "%$search%")
                                ->orWhere('postal_address', 'LIKE', "%$search%")
                                ->orWhere('address_2', 'LIKE', "%$search%")
                                ->orWhere('application_token_no', 'LIKE', "%$search%");
                        });
                    }
                })
                ->rawColumns(['checkbox', 'application_type', 'application_status', 'card_status', 'active_mobile_no', 'address', 'submitted_by'])
                ->make(true);
        }

        return view('admin.reports.index', compact('operators', 'bars', 'divisions'));
    }

    public function export(Request $request)
    {
        $application = Application::select('*')->whereIn('application_type', [1, 2, 3, 4]);
        if ($request->get('application_type')) {
            $application->where('application_type', $request->get('application_type'));
        }

        if (!empty($request->get('card_status'))) {
            $application->where('card_status', $request->get('card_status'));
        }

        if (!empty($request->get('application_status'))) {
            $application->where('application_status', $request->get('application_status'));
        }

        if ($request->get('payment_status')) {
            if ($request->get('payment_status') == 'paid') {
                $payments = Payment::where('payment_status', 1)->pluck('application_id')->toArray();
                $application->whereIn('id', $payments);
            }
            if ($request->get('payment_status') == 'unpaid') {
                $payments = Payment::where('payment_status', 0)->pluck('application_id')->toArray();
                $application->whereIn('id', $payments);
            }
        }

        if ($request->has('bar_id') && !empty($request->get('bar_id'))) {
            $application->where('voter_member_lc', $request->get('bar_id'))
                ->orWhere('voter_member_hc', $request->get('bar_id'));
        }

        if ($request->has('division_id') && !empty($request->get('division_id')) && empty($request->get('bar_id'))) {
            $districtIDs = District::where('division_id', $request->division_id)->pluck('id')->toArray();
            $tehsilIDs = Tehsil::select('id')->whereIn('district_id', $districtIDs)->pluck('id')->toArray();
            $barIDs = Bar::select('id')->whereIn('tehsil_id', $tehsilIDs)->pluck('id')->toArray();
            $application->whereIn('voter_member_lc', $barIDs)->orWhereIn('voter_member_hc', $barIDs);
        }

        if ($request->get('application_date')) {
            if ($request->get('application_date') == '1') {
                $application->whereDate('created_at', Carbon::today());
            }
            if ($request->get('application_date') == '2') {
                $application->whereDate('created_at', Carbon::yesterday());
            }
            if ($request->get('application_date') == '3') {
                $date = Carbon::now()->subDays(7);
                $application->where('created_at', '>=', $date);
            }
            if ($request->get('application_date') == '4') {
                $date = Carbon::now()->subDays(30);
                $application->where('created_at', '>=', $date);
            }
            if ($request->application_date == 5) {
                if ($request->get('application_custom_date')) {
                    $date = date("Y-m-d", strtotime($request->get('application_custom_date')));
                    $application->whereDate('created_at', $date);
                }
            }
            if ($request->application_date == 6) {
                if ($request->get('application_date_range')) {
                    $dateRange = explode(' - ', $request->application_date_range);
                    $from = date("Y-m-d", strtotime($dateRange[0]));
                    $to = date("Y-m-d", strtotime($dateRange[1]));
                    $application->whereBetween('created_at', [$from, $to]);
                }
            }
        }

        if ($request->get('application_operator')) {
            $application->where('submitted_by', $request->application_operator);
        }

        if (!empty($request->get('search'))) {
            $application->where(function ($query) use ($request) {
                $search = $request->get('search');
                $query->orWhere('advocates_name', 'LIKE', "%$search%")
                    ->orWhere('cnic_no', 'LIKE', "%$search%")
                    ->orWhere('active_mobile_no', 'LIKE', "%$search%")
                    ->orWhere('postal_address', 'LIKE', "%$search%")
                    ->orWhere('address_2', 'LIKE', "%$search%")
                    ->orWhere('application_token_no', 'LIKE', "%$search%");
            });
        }

        if ($request->export == 'exportButtonPDF') {
            view()->share([
                'applications' => $application->get(),
                'filter' => $request->all(),
            ]);
            $pdf = \PDF::loadView('admin.reports.export');
            $pdf->setPaper('A4', 'portrait');
            return $pdf->stream('Certificate.pdf', array("Attachment" => false));
        } else {
            return Excel::download(new GeneralReportExport($application->get()), 'general-report.xlsx');
        }
    }

    // **********************************************
    // ************ VPP REPORT ******************
    // **********************************************

    public function vpp(Request $request)
    {
        $admin = Auth::guard('admin')->user();
        $operators = Admin::select('id', 'name')->get();
        $bars = Bar::orderBy('name', 'asc')->get();
        $divisions = Division::orderBy('name', 'asc')->get();

        if ($request->ajax()) {

            $data = Application::vppApplication();
            $admin = Auth::guard('admin')->user();

            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('lawyer_name', function ($data) {
                    return getLawyerName($data->id);
                })
                ->addColumn('vpp_fees_year', function ($data) {
                    return isset($data->vppost->vpp_fees_year) ? $data->vppost->vpp_fees_year : '750';
                })
                ->addColumn('vpp_number', function ($data) {
                    return isset($data->vppost->vpp_number) ? $data->vppost->vpp_number : '-';
                })
                ->addColumn('vpp_delivered', function ($data) {
                    return isset($data->vppost->vpp_delivered) ? $data->vppost->vpp_delivered : 'No';
                })
                ->addColumn('vpp_returned', function ($data) {
                    return isset($data->vppost->vpp_returned) ? $data->vppost->vpp_returned : 'No';
                })
                ->addColumn('vpp_total_dues', function ($data) {
                    return isset($data->vppost->vpp_total_dues) ? $data->vppost->vpp_total_dues : '750';
                })
                ->addColumn('vpp_remarks', function ($data) {
                    return isset($data->vppost->vpp_remarks) ? $data->vppost->vpp_remarks : '-';
                })
                ->addColumn('vpp_duplicate', function ($data) {
                    return isset($data->vppost->vpp_duplicate) ? $data->vppost->vpp_duplicate : 'No';
                })
                ->filter(function ($instance) use ($request) {

                    if ($request->get('vpp_application_type')) {
                        $instance->where('application_type', $request->vpp_application_type);
                    }

                    if ($request->get('vpp_year')) {
                        $instance->whereHas('vppost', function ($query) use ($request) {
                            $query->where('created_at', 'LIKE', "%$request->vpp_year%");
                        });
                    }

                    if ($request->get('vpp_delivered')) {
                        $instance->whereHas('vppost', function ($qry) use ($request) {
                            $qry->where('vpp_delivered', $request->vpp_delivered);
                        });
                    }

                    if ($request->get('vpp_returned')) {
                        $instance->whereHas('vppost', function ($qry) use ($request) {
                            $qry->where('vpp_returned', $request->vpp_returned);
                        });
                    }

                    if ($request->get('vpp_duplicate')) {
                        $instance->whereHas('vppost', function ($qry) use ($request) {
                            $qry->where('vpp_duplicate', $request->vpp_duplicate);
                        });
                    }

                    if ($request->get('vpp_application_station')) {
                        $instance->where('voter_member_lc', $request->vpp_application_station)
                            ->orWhere('voter_member_hc', $request->vpp_application_station);
                    }

                    if ($request->has('division_id') && !empty($request->get('division_id')) && empty($request->get('vpp_application_station'))) {
                        $districtIDs = District::where('division_id', $request->division_id)->pluck('id')->toArray();
                        $tehsilIDs = Tehsil::select('id')->whereIn('district_id', $districtIDs)->pluck('id')->toArray();
                        $barIDs = Bar::select('id')->whereIn('tehsil_id', $tehsilIDs)->pluck('id')->toArray();
                        if ($request->get('vpp_application_type') == 1 || $request->get('vpp_application_type') == 4) {
                            $instance->whereIn('voter_member_lc', $barIDs);
                        } elseif ($request->get('vpp_application_type') == 2 || $request->get('vpp_application_type') == 3) {
                            $instance->whereIn('voter_member_hc', $barIDs);
                        } else {
                            $instance->whereIn('voter_member_lc', $barIDs)->orWhereIn('voter_member_hc', $barIDs);
                        }
                    }

                    if (!empty($request->get('search'))) {
                        $search = $request->get('search');
                        $instance->where('advocates_name', 'LIKE', "%$search%")
                            ->orWhere('last_name', 'LIKE', "%$search%")
                            ->orWhere('application_token_no', "$search")
                            ->orWhere('reg_no_lc', "$search");
                    }
                })
                ->rawColumns(['vpp_fees_year', 'vpp_number', 'vpp_delivered', 'vpp_returned', 'vpp_total_dues', 'vpp_remarks', 'vpp_duplicate'])
                ->make(true);
        }

        return view('admin.reports.vpp', compact('operators', 'bars', 'divisions'));
    }

    public function vppExport(Request $request)
    {
        $app_type = $request->vpp_application_type;
        $station = $request->vpp_application_station;
        $year = $request->vpp_year;
        $delivered = $request->vpp_delivered;
        $returned = $request->vpp_returned;
        $duplicate = $request->vpp_duplicate;
        $division_id = $request->division_id;

        $query = Application::vppApplication();

        if ($request->has('vpp_application_type') && !empty($app_type)) {
            $query->where('application_type', $app_type);
        }

        if (!empty($year)) {
            $query->whereHas('vppost', function ($q) use ($year) {
                $q->where('created_at', 'LIKE', "%$year%");
            });
        }

        if (!empty($delivered)) {
            $query->whereHas('vppost', function ($qry) use ($delivered) {
                $qry->where('vpp_delivered', $delivered);
            });
        }

        if (!empty($returned)) {
            $query->whereHas('vppost', function ($qry) use ($returned) {
                $qry->where('vpp_returned', $returned);
            });
        }

        if (!empty($duplicate)) {
            $query->whereHas('vppost', function ($qry) use ($duplicate) {
                $qry->where('vpp_duplicate', $duplicate);
            });
        }

        if (!empty($station)) {
            $query->where('voter_member_lc', $station)->orWhere('voter_member_hc', $station);
        }

        if (!empty($division_id) && empty($station)) {
            $districtIDs = District::where('division_id', $division_id)->pluck('id')->toArray();
            $tehsilIDs = Tehsil::select('id')->whereIn('district_id', $districtIDs)->pluck('id')->toArray();
            $barIDs = Bar::select('id')->whereIn('tehsil_id', $tehsilIDs)->pluck('id')->toArray();
            if (!empty($app_type)) {
                if ($request->get('vpp_application_type') == 1 || $request->get('vpp_application_type') == 4) {
                    $query->whereIn('voter_member_lc', $barIDs);
                } elseif ($request->get('vpp_application_type') == 2 || $request->get('vpp_application_type') == 3) {
                    $query->whereIn('voter_member_hc', $barIDs);
                }
            } else {
                $query->whereIn('voter_member_lc', $barIDs)->orWhereIn('voter_member_hc', $barIDs);
            }
        }

        $applications = $query->get();
        $applicationsID = $query->pluck('id')->toArray();

        $totalDelivered = VPP::whereIn('application_id', $applicationsID)->where('vpp_delivered', 1)->count();
        $totalReturned = VPP::whereIn('application_id', $applicationsID)->where('vpp_returned', 1)->count();

        if ($request->export == "exportPDF") {
            return view('admin.reports.vpp-pdf', compact('applications', 'app_type', 'station', 'year', 'totalDelivered', 'totalReturned'));
        } else {
            return Excel::download(new VPPReportExport($applications), 'vpp-report.xlsx');
        }
    }

    // **********************************************
    // ************ INTIMATION **********************
    // **********************************************

    public function intimationIndex(Request $request)
    {
        $admin = Auth::guard('admin')->user();
        $operators = Admin::select('id', 'name')->get();
        $bars = Bar::orderBy('name', 'asc')->get();
        $divisions = Division::orderBy('name', 'asc')->get();

        if ($request->ajax()) {
            // dd($request->all());
            $application = Application::intimationReport($request->all());
            // dd($application->get()->take(5));
            return Datatables::of($application)->addIndexColumn()->make(true);
        }

        $universities = University::select('id', 'name')->where('type', 1)->orderBy('name', 'asc')->get();
        return view('admin.reports.intimation', compact('operators', 'bars', 'divisions', 'universities'));
    }

    public function intimationExport(Request $request)
    {
        // dd($request->all());

        // dd('export');
        $bar = $request->bar_id;
        $division = $request->division_id;
        $barName = null;
        $divisionName = null;

        $filter = [
            'bar_name' => getBarName($request->bar_id),
            'division_name' => $divisionName,
            'application_date' => $request->application_date,
            'application_date_type' => $request->application_date_type,
            'custom_date_range_input' => $request->custom_date_range_input,
            'application_status' => $request->application_status,
            'payment_status' => $request->payment_status,
            // 'rcpt_date_input' => $request->rcpt_date_input,
            // 'custom_date_input' => $request->custom_date_input,
        ];

        $query = Application::intimationReport($request->all());
        // dd($query->get());

        // if (!empty($bar)) {
        //     $barName = Bar::find($bar)->name ?? null;
        //     $query->where('bar_association', $bar);
        // }

        // if ($request->get('payment_status')) {
        //     if ($request->get('payment_status') == 'paid') {
        //         $payments = Payment::where('payment_status', 1)->pluck('application_id')->toArray();
        //         $query->whereIn('id', $payments);
        //     }
        //     if ($request->get('payment_status') == 'unpaid') {
        //         $payments = Payment::where('payment_status', 0)->pluck('application_id')->toArray();
        //         $query->whereIn('id', $payments);
        //     }
        // }
        // if ($request->get('application_date')) {
        //     if ($request->get('application_date') == '1') {
        //         $query->whereDate('created_at', Carbon::today());
        //     }
        //     if ($request->get('application_date') == '2') {
        //         $query->whereDate('created_at', Carbon::yesterday());
        //     }
        //     if ($request->get('application_date') == '3') {
        //         $date = Carbon::now()->subDays(7);
        //         $query->where('created_at', '>=', $date);
        //     }
        //     if ($request->get('application_date') == '4') {
        //         $date = Carbon::now()->subDays(30);
        //         $query->where('created_at', '>=', $date);
        //     }
        //     if ($request->application_date == 5) {
        //         if ($request->get('custom_date_input')) {
        //             $date = date("Y-m-d", strtotime($request->get('custom_date_input')));
        //             $query->whereDate('created_at', $date);
        //         }
        //     }
        //     if ($request->application_date == 6) {
        //         if ($request->get('custom_date_range_input')) {
        //             $dateRange = explode(' - ', $request->custom_date_range_input);
        //             $from = date("Y-m-d", strtotime($dateRange[0]));
        //             $to = date("Y-m-d", strtotime($dateRange[1]));
        //             $query->whereBetween('created_at', [$from, $to]);
        //         }
        //     }
        //     if ($request->application_date == 7) {
        //         if ($request->get('rcpt_date_input')) {
        //             $date = date("Y-m-d", strtotime($request->get('rcpt_date_input')));
        //             $query->where('rcpt_date', $date);
        //         }
        //     }
        // }

        // if (!empty($request->get('application_status'))) {
        //     $query->where('application_status', $request->get('application_status'));
        // }


        // if (!empty($request->get('application_rcpt_no_start')) && !empty($request->get('application_rcpt_no_end'))) {
        //     $query->whereYear('rcpt_date', $request->get('application_rcpt_year'))->whereBetween('rcpt_no', [$request->get('application_rcpt_no_start'), $request->get('application_rcpt_no_end')]);
        // }

        // if (!empty($division) && empty($bar)) {
        //     $divisionName = Division::find($division)->name ?? null;
        //     $districtIDs = District::select('id')->where('division_id', $division)->pluck('id')->toArray();
        //     $tehsilIDs = Tehsil::select('id')->whereIn('district_id', $districtIDs)->pluck('id')->toArray();
        //     $barIDs = Bar::select('id')->whereIn('tehsil_id', $tehsilIDs)->pluck('id')->toArray();
        //     $query->whereIn('bar_association', $barIDs);
        // }

        $applications = $query->get();

        if ($request->export == 'exportPDF') {
            return view('admin.reports.intimation-reports-pdf', compact('applications', 'filter'));
        } else {
            return Excel::download(new IntimationReportExport($request->all()), 'intimation-report.xlsx');
        }
    }

    // **********************************************
    // ************ LOWER COURT *********************
    // **********************************************

    public function lowerCourtRcpt(Request $request)
    {
        $admin = Auth::guard('admin')->user();
        $operators = Admin::select('id', 'name')->get();
        $bars = Bar::orderBy('name', 'asc')->get();
        $divisions = Division::orderBy('name', 'asc')->get();

        if ($request->ajax()) {

            $data = LowerCourt::query();

            $admin = Auth::guard('admin')->user();

            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('lawyer_name', function ($data) {
                    return $data->lawyer_name;
                })
                ->addColumn('voter_member_lc', function ($data) {
                    return getBarName($data->voter_member_lc);
                })
                ->addColumn('mobile_no', function ($data) {
                    return $data->mobile_no;
                })
                ->addColumn('status', function ($data) {
                    return  getStatus($data->app_status) . ',' . getLcPaymentStatus($data->id)['name'];
                })
                ->filter(function ($instance) use ($request) {

                    if (!empty($request->get('app_status'))) {
                        $instance->where('app_status', $request->get('app_status'));
                    }

                    if ($request->get('payment_status')) {
                        if ($request->get('payment_status') == 'paid') {
                            $payments = Payment::where('payment_status', 1)->pluck('lower_court_id')->toArray();
                            $instance->whereIn('id', $payments);
                        }
                        if ($request->get('payment_status') == 'unpaid') {
                            $payments = Payment::where('payment_status', 0)->pluck('lower_court_id')->toArray();
                            $instance->whereIn('id', $payments);
                        }
                    }
                    if ($request->get('application_date')) {
                        if ($request->get('application_date') == '1') {
                            $instance->whereDate('created_at', Carbon::today());
                        }
                        if ($request->get('application_date') == '2') {
                            $instance->whereDate('created_at', Carbon::yesterday());
                        }
                        if ($request->get('application_date') == '3') {
                            $date = Carbon::now()->subDays(7);
                            $instance->where('created_at', '>=', $date);
                        }
                        if ($request->get('application_date') == '4') {
                            $date = Carbon::now()->subDays(30);
                            $instance->where('created_at', '>=', $date);
                        }
                        if ($request->application_date == 5) {
                            if ($request->get('application_custom_date')) {
                                $date = date("Y-m-d", strtotime($request->get('application_custom_date')));
                                $instance->whereDate('created_at', $date);
                            }
                        }
                        if ($request->application_date == 6) {
                            if ($request->get('application_date_range')) {
                                $dateRange = explode(' - ', $request->application_date_range);
                                $from = date("Y-m-d", strtotime($dateRange[0]));
                                $to = date("Y-m-d", strtotime($dateRange[1]));
                                $instance->whereBetween('created_at', [$from, $to]);
                            }
                        }
                        if ($request->application_date == 7) {
                            if ($request->get('application_rcpt_date')) {
                                $date = date("Y-m-d", strtotime($request->get('application_rcpt_date')));
                                $instance->where('rcpt_date', $date);
                            }
                        }
                    }

                    if ($request->has('bar_id') && !empty($request->get('bar_id'))) {
                        $instance->where('voter_member_lc', $request->get('bar_id'));
                    }

                    if ($request->has('division_id') && !empty($request->get('division_id')) && empty($request->get('bar_id'))) {
                        $districtIDs = District::where('division_id', $request->division_id)->pluck('id')->toArray();
                        $tehsilIDs = Tehsil::select('id')->whereIn('district_id', $districtIDs)->pluck('id')->toArray();
                        $barIDs = Bar::select('id')->whereIn('tehsil_id', $tehsilIDs)->pluck('id')->toArray();
                        $instance->whereIn('voter_member_lc', $barIDs);
                    }

                    if (!empty($request->get('search'))) {
                        $instance->where(function ($query) use ($request) {
                            $search = $request->get('search');
                            if (!empty($search['value'])) {
                                $search = $search['value'];
                                $query
                                    ->with('user')
                                    ->orWhereHas('user', function ($q) use ($search) {
                                        $q->where('name', 'like', "%$search%")
                                            ->orWhere(DB::raw('CONCAT(fname," ",lname)'), 'LIKE', '%' . $search . '%')
                                            ->orWhere(DB::raw('CONCAT("+92",phone)'), 'LIKE', '%' . $search . '%')
                                            ->orWhere(DB::raw('CONCAT("0",phone)'), 'LIKE', '%' . $search . '%')
                                            ->orWhere('cnic_no', 'LIKE', "%$search%");
                                    })
                                    ->orWhere('user_id', $search)
                                    ->orWhere('cnic_no', 'LIKE', "%$search%")
                                    ->orWhere('id', $search);
                            }
                        });
                    }
                })
                ->rawColumns(['mobile_no', 'voter_member_lc', 'status'])
                ->make(true);
        }

        $columns = ['mobile', 'status', 'rcpt_date', 'rcpt_no', 'license_no', 'plj_no', 'gi_no', 'bf_no', 'sr_no_lc', 'enr_date'];

        return view('admin.reports.lc-rcpt-report', compact('operators', 'bars', 'divisions', 'columns'));
    }

    public function indexLowerCourtLicense(Request $request)
    {

        $admin = Auth::guard('admin')->user();
        $operators = Admin::select('id', 'name')->get();
        $bars = Bar::orderBy('name', 'asc')->get();
        $divisions = Division::orderBy('name', 'asc')->get();

        if ($request->ajax()) {

            $data = LowerCourt::join('users', 'users.id', '=', 'lower_courts.user_id');
            $data->select('lower_courts.*');

            $admin = Auth::guard('admin')->user();

            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('lawyer_name', function ($data) {
                    return $data->lawyer_name . '/' . $data->father_name;
                })
                ->addColumn('voter_member_lc', function ($data) {
                    return getBarName($data->voter_member_lc);
                })
                ->filter(function ($instance) use ($request) {

                    if ($request->has('license_no')) {
                        if ($request->sort_by == 1) {
                            $instance->orderBy('users.name', 'asc');
                        }
                        if ($request->sort_by == 2) {
                            $instance->orderBy('lower_courts.license_no_lc', 'asc');
                        }
                    }

                    if ($request->get('application_date')) {
                        if ($request->get('application_date') == '1') {
                            $instance->whereDate('lower_courts.lc_date', Carbon::today());
                        }
                        if ($request->get('application_date') == '2') {
                            $instance->whereDate('lower_courts.lc_date', Carbon::yesterday());
                        }
                        if ($request->get('application_date') == '3') {
                            $date = Carbon::now()->subDays(7);
                            $instance->where('lower_courts.lc_date', '>=', $date);
                        }
                        if ($request->get('application_date') == '4') {
                            $date = Carbon::now()->subDays(30);
                            $instance->where('lower_courts.lc_date', '>=', $date);
                        }
                        if ($request->application_date == 5) {
                            if ($request->get('application_custom_date')) {
                                $date = date("Y-m-d", strtotime($request->get('application_custom_date')));
                                $instance->whereDate('lower_courts.lc_date', $date);
                            }
                        }
                        if ($request->application_date == 6) {
                            if ($request->get('application_date_range')) {
                                $dateRange = explode(' - ', $request->application_date_range);
                                $from = date("Y-m-d", strtotime($dateRange[0]));
                                $to = date("Y-m-d", strtotime($dateRange[1]));
                                $instance->whereBetween('lower_courts.lc_date', [$from, $to]);
                            }
                        }
                        if ($request->application_date == 7) {
                            if ($request->get('application_rcpt_date')) {
                                $date = date("Y-m-d", strtotime($request->get('application_rcpt_date')));
                                $instance->whereDate('lower_courts.rcpt_date', $date);
                            }
                        }
                    }

                    if (!empty($request->get('application_status'))) {
                        $instance->where('lower_courts.app_status', $request->application_status);
                    }

                    if ($request->has('bar_id') && !empty($request->get('bar_id'))) {
                        $instance->where('lower_courts.voter_member_lc', $request->get('bar_id'));
                    }

                    if ($request->has('license_no')) {
                        if ($request->license_no == 'yes') {
                            $instance->where('lower_courts.license_no_lc', '!=', NULL);
                        } else {
                            $instance->where('lower_courts.license_no_lc', NULL);
                        }
                    }

                    if ($request->has('division_id') && !empty($request->get('division_id')) && empty($request->get('bar_id'))) {
                        $districtIDs = District::where('division_id', $request->division_id)->pluck('id')->toArray();
                        $tehsilIDs = Tehsil::select('id')->whereIn('district_id', $districtIDs)->pluck('id')->toArray();
                        $barIDs = Bar::select('id')->whereIn('tehsil_id', $tehsilIDs)->pluck('id')->toArray();
                        $instance->whereIn('lower_courts.voter_member_lc', $barIDs);
                    }

                    if ($request->get('age_from') && !empty($request->get('age_from')) && $request->get('age_to') && !empty($request->get('age_to'))) {
                        $ageFrom = $request->get('age_from');
                        $ageTo = $request->get('age_to');

                        if ($ageFrom <= $ageTo) {
                            $instance->where('age', '>=', $ageFrom)->where('age', '<=', $ageTo);
                        }
                    }

                    if ($request->get('university')) {
                        $instance->whereHas('educations', function ($q) use ($request) {
                            $q->where('university_id', $request->university);
                        });
                    }

                    if (!empty($request->get('search'))) {
                        $instance->where(function ($query) use ($request) {
                            $search = $request->get('search');
                            if (!empty($search['value'])) {
                                $search = $search['value'];
                                $query->where(function ($qry) use ($search) {
                                    $qry->where('lower_courts.id', $search);
                                    $qry->orWhere('lower_courts.user_id', $search);
                                    $qry->orWhere('lower_courts.cnic_no', 'LIKE', "%$search%");
                                    $qry->orWhere('lower_courts.license_no_lc', 'LIKE', "%$search%");
                                    $qry->orWhere('users.name', 'like', "%$search%");
                                    $qry->orWhere(DB::raw('CONCAT(users.fname," ",users.lname)'), 'LIKE', '%' . $search . '%');
                                    $qry->orWhere(DB::raw('CONCAT("+92",users.phone)'), 'LIKE', '%' . $search . '%');
                                    $qry->orWhere(DB::raw('CONCAT("0",users.phone)'), 'LIKE', '%' . $search . '%');
                                    $qry->orWhere('users.cnic_no', 'LIKE', "%$search%");
                                });
                            }
                        });
                    }
                })
                ->rawColumns(['lawyer_name', 'voter_member_lc'])
                ->make(true);
        }

        $universities = University::select('id', 'name')->where('type', 1)->orderBy('name', 'asc')->get();
        $app_statuses = AppStatus::where('status', 1)->get();

        return view('admin.reports.lower-court.license.index', compact('operators', 'bars', 'divisions', 'universities', 'app_statuses'));
    }

    public function exportLowerCourtLicense(Request $request)
    {
        $instance = LowerCourt::join('users', 'users.id', '=', 'lower_courts.user_id');
        $instance->select('lower_courts.*');

        if ($request->has('license_no')) {
            if ($request->sort_by == 1) {
                $instance->orderBy('users.name', 'asc');
            }
            if ($request->sort_by == 2) {
                $instance->orderBy('lower_courts.license_no_lc', 'asc');
            }
        }

        if ($request->get('application_date')) {
            if ($request->get('application_date') == '1') {
                $instance->whereDate('lower_courts.lc_date', Carbon::today());
            }
            if ($request->get('application_date') == '2') {
                $instance->whereDate('lower_courts.lc_date', Carbon::yesterday());
            }
            if ($request->get('application_date') == '3') {
                $date = Carbon::now()->subDays(7);
                $instance->where('lower_courts.lc_date', '>=', $date);
            }
            if ($request->get('application_date') == '4') {
                $date = Carbon::now()->subDays(30);
                $instance->where('lower_courts.lc_date', '>=', $date);
            }
            if ($request->application_date == 5) {
                if ($request->get('application_custom_date')) {
                    $date = date("Y-m-d", strtotime($request->get('application_custom_date')));
                    $instance->whereDate('lower_courts.lc_date', $date);
                }
            }
            if ($request->application_date == 6) {
                if ($request->get('application_date_range')) {
                    $dateRange = explode(' - ', $request->application_date_range);
                    $from = date("Y-m-d", strtotime($dateRange[0]));
                    $to = date("Y-m-d", strtotime($dateRange[1]));
                    $instance->whereBetween('lower_courts.lc_date', [$from, $to]);
                }
            }
            if ($request->application_date == 7) {
                if ($request->get('application_rcpt_date')) {
                    $date = date("Y-m-d", strtotime($request->get('application_rcpt_date')));
                    $instance->whereDate('lower_courts.rcpt_date', $date);
                }
            }
        }

        if (!empty($request->get('application_status'))) {
            $instance->where('lower_courts.app_status', $request->application_status);
        }

        if ($request->has('bar_id') && !empty($request->get('bar_id'))) {
            $instance->where('lower_courts.voter_member_lc', $request->get('bar_id'));
        }

        if ($request->has('license_no')) {
            if ($request->license_no == 'yes') {
                $instance->where('lower_courts.license_no_lc', '!=', NULL);
            } else {
                $instance->where('lower_courts.license_no_lc', NULL);
            }
        }

        if ($request->has('division_id') && !empty($request->get('division_id')) && empty($request->get('bar_id'))) {
            $districtIDs = District::where('division_id', $request->division_id)->pluck('id')->toArray();
            $tehsilIDs = Tehsil::select('id')->whereIn('district_id', $districtIDs)->pluck('id')->toArray();
            $barIDs = Bar::select('id')->whereIn('tehsil_id', $tehsilIDs)->pluck('id')->toArray();
            $instance->whereIn('lower_courts.voter_member_lc', $barIDs);
        }

        if ($request->get('age_from') && !empty($request->get('age_from')) && $request->get('age_to') && !empty($request->get('age_to'))) {
            $ageFrom = $request->get('age_from');
            $ageTo = $request->get('age_to');

            if ($ageFrom <= $ageTo) {
                $instance->where('age', '>=', $ageFrom)->where('age', '<=', $ageTo);
            }
        }

        if ($request->get('university')) {
            $instance->whereHas('educations', function ($q) use ($request) {
                $q->where('university_id', $request->university);
            });
        }

        if (!empty($request->get('search'))) {
            $instance->where(function ($query) use ($request) {
                $search = $request->get('search');
                if (!empty($search['value'])) {
                    $search = $search['value'];
                    $query->where(function ($qry) use ($search) {
                        $qry->where('lower_courts.id', $search);
                        $qry->orWhere('lower_courts.user_id', $search);
                        $qry->orWhere('lower_courts.cnic_no', 'LIKE', "%$search%");
                        $qry->orWhere('lower_courts.license_no_lc', 'LIKE', "%$search%");
                        $qry->orWhere('users.name', 'like', "%$search%");
                        $qry->orWhere(DB::raw('CONCAT(users.fname," ",users.lname)'), 'LIKE', '%' . $search . '%');
                        $qry->orWhere(DB::raw('CONCAT("+92",users.phone)'), 'LIKE', '%' . $search . '%');
                        $qry->orWhere(DB::raw('CONCAT("0",users.phone)'), 'LIKE', '%' . $search . '%');
                        $qry->orWhere('users.cnic_no', 'LIKE', "%$search%");
                    });
                }
            });
        }

        $applications = $instance->get();

        $filter = [
            'bar_name' => getBarName($request->bar_id),
            'division_name' => getDivisionName($request->division_id),
            'application_date' => $request->application_date,
            'application_custom_date' => $request->application_custom_date,
            'application_date_range' => $request->application_date_range,
            'application_rcpt_date' => $request->application_rcpt_date,
            'application_status' => $request->application_status,
            'application_address' => $request->application_address,
        ];

        if ($request->export == 'exportPDF') {
            return view('admin.reports.lower-court.license.export', compact('applications', 'filter'));
        }
    }


    // **********************************************
    // ************ HIGH COURT *********************
    // **********************************************

    public function indexHighCourtLicense(Request $request)
    {
        $admin = Auth::guard('admin')->user();
        $operators = Admin::select('id', 'name')->get();
        $bars = Bar::orderBy('name', 'asc')->get();
        $divisions = Division::orderBy('name', 'asc')->get();
        $app_statuses = AppStatus::get();

        if ($request->ajax()) {

            $data = HighCourt::join('users as u', 'u.id', '=', 'high_courts.user_id');
            $data->select(
                'high_courts.*',
                'u.name as u_name',
                'u.father_name as u_father',
            );

            $admin = Auth::guard('admin')->user();

            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('lawyer_name', function ($data) {
                    return $data->u_name . '/' . $data->u_father;
                })
                ->addColumn('voter_member_hc', function ($data) {
                    return getBarName($data->voter_member_hc);
                })
                ->filter(function ($instance) use ($request) {

                    if ($request->has('license_no')) {
                        if ($request->sort_by == 1) {
                            $instance->orderBy('u.name', 'asc');
                        }
                        if ($request->sort_by == 2) {
                            $instance->orderBy('high_courts.license_no_hc', 'asc');
                        }
                    }

                    if ($request->get('application_date')) {
                        if ($request->get('application_date') == '1') {
                            $instance->whereDate('high_courts.enr_date_hc', Carbon::today());
                        }
                        if ($request->get('application_date') == '2') {
                            $instance->whereDate('high_courts.enr_date_hc', Carbon::yesterday());
                        }
                        if ($request->get('application_date') == '3') {
                            $date = Carbon::now()->subDays(7);
                            $instance->where('high_courts.enr_date_hc', '>=', $date);
                        }
                        if ($request->get('application_date') == '4') {
                            $date = Carbon::now()->subDays(30);
                            $instance->where('high_courts.enr_date_hc', '>=', $date);
                        }
                        if ($request->application_date == 5) {
                            if ($request->get('application_custom_date')) {
                                $date = date("Y-m-d", strtotime($request->get('application_custom_date')));
                                $instance->whereDate('high_courts.enr_date_hc', $date);
                            }
                        }
                        if ($request->application_date == 6) {
                            if ($request->get('application_date_range')) {
                                $dateRange = explode(' - ', $request->application_date_range);
                                $from = date("Y-m-d", strtotime($dateRange[0]));
                                $to = date("Y-m-d", strtotime($dateRange[1]));
                                $instance->whereBetween('high_courts.enr_date_hc', [$from, $to]);
                            }
                        }
                        if ($request->application_date == 7) {
                            if ($request->get('application_rcpt_date')) {
                                $date = date("Y-m-d", strtotime($request->get('application_rcpt_date')));
                                $instance->whereDate('high_courts.rcpt_date', $date);
                            }
                        }
                    }

                    if (!empty($request->get('application_status'))) {
                        $instance->where('high_courts.app_status', $request->application_status);
                    }

                    if ($request->has('bar_id') && !empty($request->get('bar_id'))) {
                        $instance->where('high_courts.voter_member_hc', $request->get('bar_id'));
                    }

                    if ($request->has('license_no')) {
                        if ($request->license_no == 'yes') {
                            $instance->where('high_courts.license_no_hc', '!=', NULL);
                        } else {
                            $instance->where('high_courts.license_no_hc', NULL);
                        }
                    }

                    if ($request->has('division_id') && !empty($request->get('division_id')) && empty($request->get('bar_id'))) {
                        $districtIDs = District::where('division_id', $request->division_id)->pluck('id')->toArray();
                        $tehsilIDs = Tehsil::select('id')->whereIn('district_id', $districtIDs)->pluck('id')->toArray();
                        $barIDs = Bar::select('id')->whereIn('tehsil_id', $tehsilIDs)->pluck('id')->toArray();
                        $instance->whereIn('high_courts.voter_member_hc', $barIDs);
                    }

                    if (!empty($request->get('search'))) {
                        $instance->where(function ($query) use ($request) {
                            $search = $request->get('search');
                            if (!empty($search['value'])) {
                                $search = $search['value'];
                                $query->where(function ($qry) use ($search) {
                                    $qry->where('high_courts.id', $search);
                                    $qry->orWhere('u.id', $search);
                                    $qry->orWhere('u.cnic_no', 'LIKE', "%$search%");
                                    $qry->orWhere('high_courts.license_no_hc', 'LIKE', "%$search%");
                                    $qry->orWhere('u.name', 'like', "%$search%");
                                    $qry->orWhere(DB::raw('CONCAT(u.fname," ",u.lname)'), 'LIKE', '%' . $search . '%');
                                    $qry->orWhere(DB::raw('CONCAT("+92",u.phone)'), 'LIKE', '%' . $search . '%');
                                    $qry->orWhere(DB::raw('CONCAT("0",u.phone)'), 'LIKE', '%' . $search . '%');
                                    $qry->orWhere('u.cnic_no', 'LIKE', "%$search%");
                                });
                            }
                        });
                    }
                })
                ->rawColumns(['lawyer_name', 'voter_member_hc'])
                ->make(true);
        }

        return view('admin.reports.hc-license', compact('operators', 'bars', 'divisions', 'app_statuses'));
    }

    public function exportHighCourtLicense(Request $request)
    {
        $instance = HighCourt::join('users as u', 'u.id', '=', 'high_courts.user_id');
        $instance->select(
            'high_courts.*',
            'u.name as u_name',
            'u.father_name as u_father',
        );

        if ($request->has('license_no')) {
            if ($request->sort_by == 1) {
                $instance->orderBy('u.name', 'asc');
            }
            if ($request->sort_by == 2) {
                $instance->orderBy('high_courts.license_no_hc', 'asc');
            }
        }

        if ($request->get('application_date')) {
            if ($request->get('application_date') == '1') {
                $instance->whereDate('high_courts.enr_date_hc', Carbon::today());
            }
            if ($request->get('application_date') == '2') {
                $instance->whereDate('high_courts.enr_date_hc', Carbon::yesterday());
            }
            if ($request->get('application_date') == '3') {
                $date = Carbon::now()->subDays(7);
                $instance->where('high_courts.enr_date_hc', '>=', $date);
            }
            if ($request->get('application_date') == '4') {
                $date = Carbon::now()->subDays(30);
                $instance->where('high_courts.enr_date_hc', '>=', $date);
            }
            if ($request->application_date == 5) {
                if ($request->get('application_custom_date')) {
                    $date = date("Y-m-d", strtotime($request->get('application_custom_date')));
                    $instance->whereDate('high_courts.enr_date_hc', $date);
                }
            }
            if ($request->application_date == 6) {
                if ($request->get('application_date_range')) {
                    $dateRange = explode(' - ', $request->application_date_range);
                    $from = date("Y-m-d", strtotime($dateRange[0]));
                    $to = date("Y-m-d", strtotime($dateRange[1]));
                    $instance->whereBetween('high_courts.enr_date_hc', [$from, $to]);
                }
            }
            if ($request->application_date == 7) {
                if ($request->get('application_rcpt_date')) {
                    $date = date("Y-m-d", strtotime($request->get('application_rcpt_date')));
                    $instance->whereDate('high_courts.rcpt_date', $date);
                }
            }
        }

        if (!empty($request->get('application_status'))) {
            $instance->where('high_courts.app_status', $request->application_status);
        }

        if ($request->has('bar_id') && !empty($request->get('bar_id'))) {
            $instance->where('high_courts.voter_member_hc', $request->get('bar_id'));
        }

        if ($request->has('license_no')) {
            if ($request->license_no == 'yes') {
                $instance->where('high_courts.license_no_hc', '!=', NULL);
            } else {
                $instance->where('high_courts.license_no_hc', NULL);
            }
        }

        if ($request->has('division_id') && !empty($request->get('division_id')) && empty($request->get('bar_id'))) {
            $districtIDs = District::where('division_id', $request->division_id)->pluck('id')->toArray();
            $tehsilIDs = Tehsil::select('id')->whereIn('district_id', $districtIDs)->pluck('id')->toArray();
            $barIDs = Bar::select('id')->whereIn('tehsil_id', $tehsilIDs)->pluck('id')->toArray();
            $instance->whereIn('high_courts.voter_member_hc', $barIDs);
        }

        if (!empty($request->get('search'))) {
            $instance->where(function ($query) use ($request) {
                $search = $request->get('search');
                if (!empty($search['value'])) {
                    $search = $search['value'];
                    $query->where(function ($qry) use ($search) {
                        $qry->where('high_courts.id', $search);
                        $qry->orWhere('u.id', $search);
                        $qry->orWhere('u.cnic_no', 'LIKE', "%$search%");
                        $qry->orWhere('high_courts.license_no_hc', 'LIKE', "%$search%");
                        $qry->orWhere('u.name', 'like', "%$search%");
                        $qry->orWhere(DB::raw('CONCAT(u.fname," ",u.lname)'), 'LIKE', '%' . $search . '%');
                        $qry->orWhere(DB::raw('CONCAT("+92",u.phone)'), 'LIKE', '%' . $search . '%');
                        $qry->orWhere(DB::raw('CONCAT("0",u.phone)'), 'LIKE', '%' . $search . '%');
                        $qry->orWhere('u.cnic_no', 'LIKE', "%$search%");
                    });
                }
            });
        }

        $applications = $instance->get();

        $filter = [
            'bar_name' => getBarName($request->bar_id),
            'division_name' => getDivisionName($request->division_id),
            'application_date' => $request->application_date,
            'application_custom_date' => $request->application_custom_date,
            'application_date_range' => $request->application_date_range,
            'application_rcpt_date' => $request->application_rcpt_date,
            'application_status' => $request->application_status,
            'application_address' => $request->application_address,
        ];

        if ($request->export == 'exportPDF') {
            return view('admin.reports.hc-license-export', compact('applications', 'filter'));
        }
    }

    // **********************************************
    // ************ VOTER MEMBER ********************
    // **********************************************

    public function indexVoterMember()
    {
        return view('admin.reports.voter-member-index');
    }

    public function exportVoterMember(Request $request)
    {
        $filter = $request->filter;

        if (!$filter['search_voter_member']) {
            return redirect()->back()->with('error', 'Please select voter member to export the report.');
        }

        $records = voterMemberReport($filter)->get();

        return view('admin.reports.voter-member-export', compact('records', 'filter'));
    }

    // **********************************************
    // ************ LAWYER SUMMARY REPORT *****************
    // **********************************************

    public function indexLawyerSummaryReport()
    {
        return view('admin.reports.lawyer-summary-index');
    }

    public function exportLawyerSummaryReport(Request $request)
    {

        $filter = $request->filter;

        $filter['search_voter_member'] = $request->bar_id;
        $filter['search_user_type'] = $request->type;


        if (!$filter['search_voter_member']) {
            return redirect()->back()->with('error', 'Please select voter member to export the report.');
        }

        if ($request->type == "all") {
            $records = lawyerSummaryReportEx($filter);
            $hc = $records['HC']->get()->toArray();
            $lc = $records['LC']->get()->toArray();

            if (count($hc) > 0 && count($lc) > 0) {
                $records = array_merge($hc, $lc);
            } elseif (count($hc) > 0 && count($lc) == 0) {
                $records = $hc;
            } elseif (count($lc)) {
                $records = $lc;
            } else {
                $records = [];
            }
        } else {
            $records = lawyerSummaryReportEx($filter)->get();
        }



        return view('admin.reports.lawyer-summary-export', compact('records', 'filter'));
    }

    // public function generateAndDownloadPdf(Request $request)
    // {
    //     $users = User::select('id','name')->take(5000)->get();
    //     dispatch(new GeneratePdfJob($users));

    //     dd('loading ...');
    //     // $path = Carbon::parse(Carbon::now())->format('dmy');
    //     // return response()->download(storage_path('pdf/file_' . $path . '.pdf'));
    // }

    // **********************************************
    // ************ GENERAL SEARCH REPORT *****************
    // **********************************************

    public function generalSearchReport()
    {
        if (!permission('general_report')) {
            abort(403);
        }

        return view('admin.reports.general-search-report');
    }
}
