<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\VPApplicationExport;
use App\Imports\QueueImport;
use App\Imports\VPPNumberImport;
use App\Imports\VPPReturnImport;
use Yajra\DataTables\DataTables;
use Carbon\Carbon;

use App\PrintSecureCard;
use App\PrintCertificate;
use App\Application;
use App\Admin;
use App\Bar;
use App\GcHighCourt;
use App\GcLowerCourt;
use App\LowerCourt;
use App\Vpp;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PDF;
use ZipArchive;
use Illuminate\Support\Facades\Storage;

class SecureCardController extends Controller
{
    public function index()
    {
        return view('admin.secure-card.index');
    }

    // public function index(Request $request)
    // {
    //     if (Route::currentRouteName() == 'secure-card.lower-court') {
    //         $type = 1;
    //         $title = 'Lower Court';
    //     } elseif (Route::currentRouteName() == 'secure-card.renewal-lower-court') {
    //         $type = 4;
    //         $title = 'Renewal Lower Court';
    //     } elseif (Route::currentRouteName() == 'secure-card.higher-court') {
    //         $type = 2;
    //         $title = 'Higher Court';
    //     } elseif (Route::currentRouteName() == 'secure-card.renewal-higher-court') {
    //         $type = 3;
    //         $title = 'Renewal Higher Court';
    //     } else {
    //         return back()->with('error', 'invalid Request');
    //     }
    //     $admin = Auth::guard('admin')->user();
    //     $operators = Admin::select('id', 'name')->get();
    //     $bars =  Bar::select('id', 'name')->get();
    //     if ($request->ajax()) {
    //         $application = Application::select('*')->where('application_type', $type)->where('is_approved', 1);
    //         $admin = Auth::guard('admin')->user();

    //         return Datatables::of($application)
    //             ->addIndexColumn()
    //             ->addColumn('checkbox', function (Application $application) {
    //                 $checkbox = '<input type="checkbox" class="application_checked" name="application_token_no[]" value="' . $application->id . '"/>';
    //                 return $checkbox;
    //             })
    //             ->addColumn('application_type', function (Application $application) {
    //                 return getApplicationType($application->id);
    //             })
    //             ->addColumn('active_mobile_no', function (Application $application) {
    //                 $activeMobileNumber = "+92" . $application->active_mobile_no;
    //                 return $activeMobileNumber;
    //             })
    //             ->addColumn('application_status', function (Application $application) {
    //                 return getApplicationStatus($application->id);
    //             })
    //             ->addColumn('card_status', function (Application $application) {
    //                 return getCardStatus($application->id);
    //             })
    //             ->addColumn('address', function (Application $application) {
    //                 $address = $application->postal_address . ' ' . $application->address_2;
    //                 return $address;
    //             })
    //             ->addColumn('submitted_by', function (Application $application) {
    //                 if (isset($application->submitted_by)) {
    //                     $submittedBy = getAdminName($application->submitted_by);
    //                 } else {
    //                     $submittedBy = "Online User";
    //                 }
    //                 return $submittedBy;
    //             })
    //             ->addColumn('voter_member_lc', function (Application $application) {
    //                 $voter_member = getBarName($application->voter_member_lc);
    //                 return $voter_member;
    //             })
    //             ->addColumn('voter_member_hc', function (Application $application) {
    //                 $voter_member = getBarName($application->voter_member_hc);
    //                 return $voter_member;
    //             })
    //             ->addColumn('action', function (Application $application) {

    //                 $btn = '<a href="' . route('applications.show', $application->id) . '">
    //                 <span class="badge badge-primary mr-1 mr-1"><i class="fas fa-eye mr-1" aria-hidden="true"></i>View</span></a>';

    //                 if (Auth::guard('admin')->user()->hasPermission('edit-applications')) {
    //                     $btn .= '<a href="' . route('applications.edit', $application->id) . '"><span class="badge badge-primary mr-1"><i class="far fa-edit mr-1" aria-hidden="true"></i>Edit</span></a>';
    //                 }

    //                 if (Auth::guard('admin')->user()->hasPermission('delete-applications')) {
    //                     $btn .= ' <a href="javascript:void(0)" data-action="' . route('applications.destroy', $application->id) . '" onclick="deleteApplication(this)"><span class="badge badge-danger mr-1"><i class="fas fa-trash-alt mr-1" aria-hidden="true"></i>Delete</span></a>';
    //                 }

    //                 $btn .= '<a href="' . route('applications.pdf-view', ['download' => 'pdf', 'application' => $application]) . '">
    //                 <span class="badge badge-success mr-1"><i class="fas fa-download mr-1" aria-hidden="true"></i>Download PDF</span></a>';

    //                 $btn .= '<div class="printCertificateSection d-inline-flex"><a class="certificatePrint" href="javascript:void(0)" data-action="' . route('applications.certificate', ['download' => 'pdf', 'application' => $application]) . '">
    //                     <span class="badge badge-success mr-1"><i class="fas fa-print mr-1" aria-hidden="true"></i>Print Certificate</span></a></div>';

    //                 $btn .= '<a href="' . route('applications.exportPDFs', ['download' => 'pdf', 'ids' => $application->id]) . '" target="_blank">
    //                 <span class="badge badge-success mr-1"><i class="fas fa-print mr-1" aria-hidden="true"></i>Print VP Letter</span></a>';

    //                 $btn .= '<a href="' . route('applications.exportPrint', ['download' => 'pdf', 'ids' => $application->id]) . '" target="_blank">
    //                 <span class="badge badge-success mr-1"><i class="fas fa-print mr-1" aria-hidden="true"></i>Print VP Envelop</span></a>';

    //                 return $btn;
    //             })
    //             ->filter(function ($instance) use ($request, $type) {

    //                 if ($request->get('application_type')) {
    //                     $instance->where('application_type', $request->get('application_type'));
    //                 }

    //                 if ($request->get('application_date')) {
    //                     if ($request->get('application_date') == '1') {
    //                         $instance->whereDate('created_at', Carbon::today());
    //                     }
    //                     if ($request->get('application_date') == '2') {
    //                         $instance->whereDate('created_at', Carbon::yesterday());
    //                     }
    //                     if ($request->get('application_date') == '3') {
    //                         $date = Carbon::now()->subDays(7);
    //                         $instance->where('created_at', '>=', $date);
    //                     }
    //                     if ($request->get('application_date') == '4') {
    //                         $date = Carbon::now()->subDays(30);
    //                         $instance->where('created_at', '>=', $date);
    //                     }
    //                     if ($request->application_date == 5) {
    //                         if ($request->get('application_date_range')) {
    //                             $dateRange = explode(' - ', $request->application_date_range);
    //                             $from = date("Y-m-d", strtotime($dateRange[0]));
    //                             $to = date("Y-m-d", strtotime($dateRange[1]));
    //                             $instance->whereBetween('created_at', [$from, $to]);
    //                         }
    //                     }
    //                 }

    //                 if ($request->get('application_operator')) {
    //                     $instance->where('submitted_by', $request->application_operator);
    //                 }

    //                 if (!empty($request->get('bar_id'))) {
    //                     if ($type == 1 || $type == 4) {
    //                         $instance->where('voter_member_lc', $request->bar_id);
    //                     } elseif ($type == 2 || $type == 3) {
    //                         $instance->where('voter_member_hc', $request->bar_id);
    //                     }
    //                 }

    //                 if (!empty($request->get('card_status'))) {
    //                     $instance->where('card_status', $request->card_status);
    //                 }

    //                 if (!empty($request->get('preg_column')) && !empty($request->get('preg_search'))) {
    //                     $pregColumn = $request->get('preg_column');
    //                     $pregSearch = $request->get('preg_search');
    //                     $instance->where($pregColumn, 'LIKE', "%$pregSearch%");
    //                 }

    //                 if (!empty($request->get('search'))) {
    //                     $instance->where(function ($query) use ($request) {
    //                         $search = $request->get('search');
    //                         $query->orWhere('advocates_name', 'LIKE', "%$search%")
    //                             ->orWhere('cnic_no', 'LIKE', "%$search%")
    //                             ->orWhere('active_mobile_no', 'LIKE', "%$search%")
    //                             ->orWhere('postal_address', 'LIKE', "%$search%")
    //                             ->orWhere('address_2', 'LIKE', "%$search%")
    //                             ->orWhere('reg_no_lc', $search)
    //                             ->orWhere('bf_no_lc', $search)
    //                             ->orWhere('bf_no_hc', $search)
    //                             ->orWhere('application_token_no', "$search");
    //                     });
    //                 }
    //             })
    //             ->rawColumns(['checkbox', 'application_type', 'application_status', 'card_status', 'action', 'active_mobile_no', 'address', 'submitted_by'])
    //             ->make(true);
    //     }

    //     return view('admin.applications.secure-card.index', compact('operators', 'title', 'type', 'bars'));
    // }

    public function vpIndex(Request $request)
    {

        if (Route::currentRouteName() == 'secure-card.queue.lower-court') {
            $type = 1;
            $title = 'Lower Court';
        } elseif (Route::currentRouteName() == 'secure-card.queue.renewal-lower-court') {
            $type = 4;
            $title = 'Renewal Lower Court';
        } elseif (Route::currentRouteName() == 'secure-card.queue.higher-court') {
            $type = 2;
            $title = 'Higher Court';
        } elseif (Route::currentRouteName() == 'secure-card.queue.renewal-higher-court') {
            $type = 3;
            $title = 'Renewal Higher Court';
        } else {
            return back()->with('error', 'invalid Request');
        }


        $admin = Auth::guard('admin')->user();
        $operators = Admin::select('id', 'name')->get();

        if ($request->ajax()) {
            $printSecureCard = PrintSecureCard::select('*')->whereHas('applications', function ($q) use ($type) {
                $q->where('application_type', $type);
            })->where('is_printed', 0)->where('admin_id', $admin->id)->orderBy('id', 'DESC');
            $admin = Auth::guard('admin')->user();

            return Datatables::of($printSecureCard)
                ->addIndexColumn()
                ->addColumn('checkbox', function (PrintSecureCard $data) {
                    $checkbox = '<input type="checkbox" class="application_checked" name="applications[]" value="' . $data->id . '"/>';
                    return $checkbox;
                })
                ->addColumn('application_id', function (PrintSecureCard $data) {

                    $btn = getApplicationTokenNo($data->application_id);
                    return $btn;
                })
                ->addColumn('advocates_name', function (PrintSecureCard $data) {

                    $btn = $data->application->advocates_name;
                    return $btn;
                })
                ->addColumn('reg_no_lc', function (PrintSecureCard $data) {

                    $btn = $data->application->reg_no_lc; // Registration/Legder #
                    return $btn;
                })
                ->addColumn('cnic_no', function (PrintSecureCard $data) {

                    $btn = $data->application->cnic_no;
                    return $btn;
                })
                ->addColumn('submitted_by', function (PrintSecureCard $data) {
                    if (isset($data->admin_id)) {
                        $submittedBy = getAdminName($data->admin_id);
                    } else {
                        $submittedBy = "Online User";
                    }
                    return $submittedBy;
                })
                ->addColumn('status', function (PrintSecureCard $data) {
                    if ($data->is_printed == 0) {
                        $btn = '<label class="badge badge-warning">In Printing Queue</label>';
                    }
                    return $btn;
                })

                ->rawColumns(['checkbox', 'status', 'submitted_by', 'reg_no_lc'])
                ->make(true);
        }

        return view('admin.applications.secure-card.queue-listing', compact('operators', 'title', 'type'));
    }

    public function selectAll(Request $request)
    {


        $application = Application::select('*');
        if ($request->get('application_type')) {
            $application->where('application_type', $request->get('application_type'));
            $type = $request->get('application_type');
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

        if ($request->has('bar_id')) {
            if ($type == 1 || $type == 4) {
                $instance->where('voter_member_lc', $request->bar_id);
            } elseif ($type == 2 || $type == 3) {
                $instance->where('voter_member_hc', $request->bar_id);
            }
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

        $admin = Auth::guard('admin')->user();
        return response()->json(['application_id' => $application->pluck('id'), 'status' => 1]);
    }

    public function exportBulk(Request $request)
    {
        $admin = Auth::guard('admin')->user();
        $type = $request->type;
        $exports = PrintSecureCard::select('id', 'application_id')->whereHas('applications', function ($q) use ($type) {
            $q->where('application_type', $type);
        })->where('is_printed', 0)->where('admin_id', $admin->id);
        if ($exports->count() == 0) {
            return redirect()->route('applications.vpIndex')->with('error', 'No record to export');
        }
        $ids = $exports->pluck('application_id')->toArray();
        $exports->update([
            'is_printed' => 1,
            'printed_at' => Carbon::now(),
        ]);
        if (empty($ids)) {
            return response()->json([
                'status' => 0,
                'message' => 'Nothing to export',
            ]);
        } else {
            return response()->json([
                'status' => 1,
                'ids' => implode(',', $ids),
                'pdf' => \URL::route('secure-card.export.pdfs'),
                'print' => \URL::route('secure-card.export.print'),
                'excel' => \URL::route('secure-card.export.excel'),
            ]);
        }
    }

    public function exportPDFs(Request $request)
    {
        $ids = $request->ids;
        $ids = explode(',', $ids);
        $applications = Application::orderBy('id', 'asc')->select('id', 'reg_no_lc')->whereIn('id', $ids)->get();
        view()->share([
            'applications' => $applications
        ]);
        $pdf = PDF::loadView('admin.applications.vp-print-1');
        $pdf->setPaper('A4', 'portrait');
        return $pdf->stream('Certificate.pdf', array("Attachment" => false));
    }

    public function exportPrint(Request $request)
    {
        $ids = $request->ids;
        $ids = explode(',', $ids);
        $applications = Application::orderBy('id', 'asc')->select('id', 'postal_address', 'address_2', 'active_mobile_no', 'reg_no_lc')->whereIn('id', $ids)->get();
        view()->share([
            'applications' => $applications
        ]);
        $pdf = PDF::loadView('admin.applications.vp-print-2');
        $pdf->setPaper('A4', 'portrait');
        return $pdf->stream('Letter.pdf', array("Attachment" => false));
    }

    public function exportExcel(Request $request)
    {
        $appType = $request->app_type;
        $ids = explode(',', $request->ids);
        try {
            return Excel::download(
                new VPApplicationExport((int)$appType, $ids),
                getApplicationType($appType) . ' Application ' . date('d_m_Y_h_i_a', strtotime(Carbon::now())) . '.xlsx'
            );
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', 'The data you want to export in invalid or incomplete. Please try again later');
        }
    }

    public function queueImport(Request $request)
    {
        Excel::import(new QueueImport($request->excel_import_app_type), request()->file('excel_import_file'));
        return response()->json(['status' => 1, 'message' => 'success']);
    }

    public function printingDetails($id)
    {
        $application = Application::with(['prints' => function ($query) {
            $query->orderBy('id', 'desc');
        }])->find($id);
        return view('admin.applications.secure-card.printing-details', compact('application'));
    }

    public function vppNumberImport(Request $request)
    {
        Excel::import(new VPPNumberImport($request->excel_import_app_type), request()->file('excel_import_file'));
        return response()->json(['status' => 1, 'message' => 'success']);
    }

    public function vppReturnImport(Request $request)
    {
        Excel::import(new VPPReturnImport($request->excel_import_app_type), request()->file('excel_import_file'));
        return response()->json(['status' => 1, 'message' => 'success']);
    }

    public function vppReturnBackPrint(Request $request)
    {
        $application = Application::find($request->application);
        view()->share('application', $application);
        if ($request->has('download')) {
            $pdf = PDF::loadView('admin.applications.secure-card.vpp-return-back-print');
            return $pdf->stream('APPLICATION-' . $application->application_token_no . '.pdf');
        }
        return view('admin.applications.secure-card.vpp-return-back-print');
    }

    public function vppReturnBackStatus(Request $request)
    {
        $vpp = VPP::where('application_id', $request->application_id)->first();

        if (isset($vpp)) {
            if ($request->vpp_return_back == 1) {
                $vpp->update([
                    'vpp_return_back' => true,
                    'vpp_return_back_at' => Carbon::now(),
                ]);
            } else {
                $vpp->update([
                    'vpp_return_back' => false,
                    'vpp_return_back_at' => null,
                ]);
            }
        }

        return redirect()->back();
    }

    public function exportImages($encoded_data)
    {
        try {
            $decoded_data = base64_decode($encoded_data);
            $decoded_data_array = \explode('|', $decoded_data);
            $app_type = $decoded_data_array[0];
            $record_ids_array = \explode(',', $decoded_data_array[1]);

            $records = GcHighCourt::select('m.id as m_id', 'm.file_name as m_filename', 'gc_high_courts.cnic_no as cnic_no')
                ->join('media as m', 'm.model_id', 'gc_high_courts.user_id')
                ->where('m.model_type', 'App\User')
                ->where('m.collection_name', 'gc_profile_image')
                ->whereIn('gc_high_courts.id', $record_ids_array)
                ->get();

            $tempDir = storage_path('temp_downloads');
            if (!file_exists($tempDir)) {
                mkdir($tempDir);
            }

            $zipFile = storage_path('temp_downloads/images.zip');
            $zip = new ZipArchive;
            $zip->open($zipFile, ZipArchive::CREATE);

            foreach ($records as $key => $record) {
                $path = $record->m_id . '/' . $record->m_filename;
                if (Storage::exists($path)) {
                    $newFileName = $record->cnic_no . '.' . pathinfo($record->m_filename, PATHINFO_EXTENSION);
                    $zip->addFile(Storage::path($path), $newFileName);
                }
            }

            return response()->download($zipFile, 'images.zip')->deleteFileAfterSend();
        } catch (\Throwable $th) {
            // throw $th;
            abort(403, $th->getMessage());
        }
    }

    public function exportLettersEnvelops($encoded_data)
    {
        try {
            $decoded_data = base64_decode($encoded_data);
            $decoded_data_array = \explode('|', $decoded_data);
            $app_type = $decoded_data_array[0];
            $print_type = $decoded_data_array[1];
            $record_ids_array = PrintSecureCard::get()->pluck('application_id')->toArray();

            if ($app_type == 'lower_court') {
                $gc_lc = GcLowerCourt::orderBy('gc_lower_courts.date_of_enrollment_lc', 'asc');
                $gc_lc->leftJoin('users as u', 'u.id', 'gc_lower_courts.user_id');
                $gc_lc->leftJoin('bars as b', 'b.id', '=', 'gc_lower_courts.voter_member_lc');
                $gc_lc->select(
                    'gc_lower_courts.reg_no_lc',
                    'gc_lower_courts.lawyer_name as lawyer_name_full',
                    DB::raw('CASE WHEN gc_lower_courts.contact_no THEN CONCAT("0", SUBSTRING(gc_lower_courts.contact_no, 1, 3), "-", SUBSTRING(gc_lower_courts.contact_no, 4)) ELSE "N/A" END  as contact_no'),
                    'gc_lower_courts.address_1',
                    'gc_lower_courts.address_2',
                );

                $gc_lc->whereIn('gc_lower_courts.id', $record_ids_array);
                $gc_lc->where('gc_lower_courts.app_status', 1);
                $gc_lc_records = $gc_lc->get()->toArray();

                // dd($gc_lc_records);

                $lc = LowerCourt::query()
                    ->select(
                        'lower_courts.reg_no_lc',
                        'u.name as lawyer_name_full',
                        DB::raw('CONCAT("0", SUBSTRING(u.phone, 1, 3), "-", SUBSTRING(u.phone, 4)) as contact_no'),
                        DB::raw('CONCAT(
                            la.ha_house_no,
                            ", ",
                            la.ha_str_address,
                            ", ",
                            la.ha_town,
                            ", ",
                            la.ha_city
                        ) as address_1'),
                        DB::raw('CONCAT("") as address_2'),
                    )
                    ->join('users as u', 'u.id', 'lower_courts.user_id')
                    ->leftJoin('lawyer_addresses as la', 'lower_courts.id', '=', 'la.lower_court_id')
                    ->orderBy('lower_courts.date_of_enrollment_lc', 'asc');


                $lc->whereIn('lower_courts.id', $record_ids_array);
                $lc->where('lower_courts.app_status', 1);
                $lc_records = $lc->get()->toArray();

                // dd($lc_records);

                $results = collect(array_merge($gc_lc_records, $lc_records));
            }

            view()->share([
                'applications' => $results
            ]);

            if ($print_type == 1) {
                $pdf = PDF::loadView('admin.applications.vp-print-1');
            }

            if ($print_type == 2) {
                $pdf = PDF::loadView('admin.applications.vp-print-2');
            }


            $pdf->setPaper('A4', 'portrait');
            return $pdf->download('Envelop-Letter.pdf', array("Attachment" => false));
        } catch (\Throwable $th) {
            // throw $th;
            abort(403, $th->getMessage());
        }
    }
}
