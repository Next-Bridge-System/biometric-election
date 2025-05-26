<?php

namespace App\Http\Controllers\Admin;

use App\GcLowerCourt;
use App\Http\Controllers\Controller;
use App\LawyerEducation;
use App\LowerCourt;
use App\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use PDF;
use Auth;
use Carbon\Carbon;
use Picqer\Barcode\BarcodeGeneratorDynamicHTML;
use setasign\Fpdi\Fpdi;

class PrintLowerCourtController extends Controller
{
    public function printBankVoucher(Request $request)
    {
        $application = LowerCourt::find($request->lower_court_id);
        $payments = Payment::where('lower_court_id', $application->id)->where('is_voucher_print', 1)->get();

        view()->share([
            'application' => $application,
            'payments' => $payments,
        ]);
        if ($request->has('download')) {
            $pdf = PDF::loadView('admin.lower-court.prints.payment-vouchers');
            $pdf->setPaper('A4', 'landscape');
            return $pdf->stream('Habib-Bank-Limited-Voucher-' . $application->id . '.pdf', array("Attachment" => false));
        }
        return back()->with('error', 'No Payment To Download');
    }

    public function printFormLc(Request $request)
    {
        $application = LowerCourt::with('educations')->find($request->application);
        $payments = Payment::where('user_id', $application->user_id)->where('application_type', 6)->get();
        $educations = LawyerEducation::with('university')->where('lower_court_id', $application->id)->get();
        
        view()->share([
            'application' => $application,
            'payments' => $payments,
            'educations' => $educations,
        ]);

        if ($request->has('download')) {
            $pdf = PDF::loadView('admin.lower-court.prints.form-A');
            $pdf->setPaper('Legal', 'portrait');
            return $pdf->stream('Lower-court-Form-A-' . $application->id . '.pdf', array("Attachment" => false));
        }

        return view('admin.lower-court.prints.form-A');
    }

    public function printToken(Request $request)
    {
        $application = LowerCourt::find($request->application);
        view()->share('application', $application);
        if ($request->has('download')) {
            $pdf = PDF::loadView('admin.lower-court.prints.token');
            $pdf->setPaper('A4', 'portrait');
            return $pdf->stream('LC-Token-' . $application->id . '.pdf', array("Attachment" => false));
        }
        return view('admin.lower-court.prints.token');
    }

    public function printCandidateInterview($id)
    {
        if (!Auth::guard('admin')->user()->hasPermission('print-interview-letter-lc')) {
            abort(403, 'You don\'t have permission to print candidate interview letter.');
        }
        $application = LowerCourt::find($id);
        view()->share('application', $application);
        $pdf = PDF::loadView('pdf.lower-court.interview');
        $pdf->setPaper('A4', 'portrait');
        return $pdf->stream('lc-interview-candidate-letter-' . $application->id . '.pdf', array("Attachment" => false));
        return view('pdf.lower-court.interview');
    }

    public function printDetail($id)
    {
        if (!Auth::guard('admin')->user()->hasPermission('print-detail-lc')) {
            abort(403, 'You don\'t have permission to print lower court detail.');
        }
        $application = LowerCourt::find($id);
        view()->share('application', $application);
        $pdf = PDF::loadView('pdf.lower-court.detail');
        $pdf->setPaper('A4', 'portrait');
        return $pdf->stream('lc-detail-' . $application->id . '.pdf', array("Attachment" => false));
        return view('pdf.lower-court.detail');
    }

    public function printFormB($id)
    {
        $lc = LowerCourt::find($id);
        // $application = Application::where('user_id', $lc->user_id)->where('is_intimation_completed', 1)->firstOrFail();
        $application = LowerCourt::where('user_id', $lc->user_id)->first();

        view()->share([
            'application' => $application,
        ]);

        $pdf = PDF::loadView('pdf.lower-court.form-B');
        $pdf->setPaper('A4', 'potrait');
        return $pdf->stream('certificate-of-training-' . Carbon::now() . '.pdf', array("Attachment" => false));
    }

    public function printFormAffidavit($id)
    {
        $application = LowerCourt::where('id', $id)->where('is_final_submitted', 1)->firstOrFail();

        view()->share([
            'application' => $application,
        ]);

        $pdf = PDF::loadView('admin.lower-court.prints.criminal-affidavit');
        $pdf->setPaper('A4', 'potrait');
        return $pdf->stream('nomination-of-lawyers-group-benefit-scheme-' . Carbon::now() . '.pdf', array("Attachment" => false));
    }

    public function printUndertakingForm($id)
    {
        $application = LowerCourt::where('id', $id)->where('is_final_submitted', 1)->firstOrFail();

        view()->share([
            'application' => $application,
        ]);

        $pdf = PDF::loadView('admin.lower-court.prints.undertaking-form');
        $pdf->setPaper('A4', 'potrait');
        return $pdf->stream('nomination-of-lawyers-group-benefit-scheme-' . Carbon::now() . '.pdf', array("Attachment" => false));
    }

    public function printFormE($id)
    {
        $application = LowerCourt::where('id', $id)->where('is_final_submitted', 1)->firstOrFail();

        view()->share([
            'application' => $application,
        ]);

        $pdf = PDF::loadView('admin.lower-court.prints.form-E');
        $pdf->setPaper('A4', 'potrait');
        return $pdf->stream('nomination-of-lawyers-group-benefit-scheme-' . Carbon::now() . '.pdf', array("Attachment" => false));
    }

    public function printFormG($id)
    {
        $application = LowerCourt::where('id', $id)->where('is_final_submitted', 1)->firstOrFail();

        view()->share([
            'application' => $application,
        ]);

        $pdf = PDF::loadView('admin.lower-court.prints.form-G');
        $pdf->setPaper('A4', 'potrait');
        return $pdf->stream('nomination-of-lawyers-group-benefit-scheme-' . Carbon::now() . '.pdf', array("Attachment" => false));
    }

    public function printShortDetail(Request $request)
    {
        try {

            view()->share([
                'data' => lowerCourtShortDetail($request->id, $request->type),
            ]);

            $pdf = PDF::loadView('admin.lower-court.prints.short-detail');
            $pdf->setPaper('legal', 'potrait');
            return $pdf->stream('lc-short-detail-' . Carbon::now() . '.pdf', array("Attachment" => false));
        } catch (\Throwable $th) {
            throw $th;
            abort(403);
        }
    }

    public function printAdvocateCertificate($id)
    {
        $application = LowerCourt::find($id);
        if (!getAdvocateCertificateStatus($application)) {
            abort(403);
        }

        try {
            $filePath = public_path("admin/files/advocate-certificate.pdf");
            $outputFilePath = storage_path("app/public/lc-advocate-certificates/" . $application->id . ".pdf");
            $this->fill_pdf_file($filePath, $outputFilePath, $id);
            return response()->file($outputFilePath);
        } catch (\Throwable $th) {
            abort(403, $th->getMessage());
        }
    }

    public function fill_pdf_file($file, $outputFilePath, $id)
    {
        $application = LowerCourt::find($id);

        $lc_date_jS = Carbon::parse($application->lc_date)->format('jS');
        $lc_date_F = Carbon::parse($application->lc_date)->format('F Y');
        $lc_date_ymd = Carbon::parse($application->lc_date)->format('Y-m-d');
        $voter_member = strtoupper($application->father_name) . ' of ' . strtoupper(getVoterMemberName($application->voter_member_lc));
        $laywer_name = isset($application->lawyer_name) ? strtoupper($application->lawyer_name) : '';
        $vc = "";
        //$vc = strtoupper('Kamran Bashir Mughal');
        $sctry = "";
        //$sctry = strtoupper('Rafaqat Ali Sohal');

        $fpdi = new FPDI;
        $count = $fpdi->setSourceFile($file);
        $vcImage = null;
        $sctryImage = null;

        $memberVC = getMemberForCertificate($lc_date_ymd,"Vice Chairman");
        if ($memberVC != null) {
            $vc = $memberVC->name ?? $vc;
            $vcImage =  storage_path("app/public/". $memberVC->signature_url);
            Log::info("vcImage");
            Log::info($vcImage);
        }
        $memberSctry = getMemberForCertificate($lc_date_ymd, "Secretary");
        if ($memberSctry != null) {
            $sctry = $memberSctry->name ?? $sctry;
            $sctryImage = storage_path("app/public/". $memberSctry->signature_url);
            Log::info("sctryImage");
            Log::info($sctryImage);
        }

        for ($i = 1; $i <= $count; $i++) {
            $template = $fpdi->importPage($i);
            $size = $fpdi->getTemplateSize($template);
            $fpdi->AddPage($size['orientation'], array($size['width'], $size['height']));
            $fpdi->useTemplate($template);

            $fpdi->SetFont("Courier", "B", 16);
            $fpdi->SetTextColor(0, 0, 0);

            $fpdi->Text(161, 18, $application->license_no_lc);

            $fpdi->Text(70, 95, $laywer_name);
            $fpdi->Text(85, 108, $voter_member);

            $fpdi->Text(50, 121, $lc_date_jS);
            $fpdi->Text(140, 121, $lc_date_F);

            $fpdi->Text(50, 210, $lc_date_jS);
            $fpdi->Text(140, 210, $lc_date_F);

            $fpdi->Text(10, 260, $sctry);
            $fpdi->Text(140, 260, $vc);

            //$fpdi->Image("https://portal.pbbarcouncil.com/public/admin/images/sctry.png", 25, 230);
            if ($sctryImage != null) {
                $fpdi->Image($sctryImage, 18, 230,50,'auto');
            }
            //$fpdi->Image("https://portal.pbbarcouncil.com/public/admin/images/vc.png", 155, 230);
            if($vcImage != null){
                $fpdi->Image($vcImage, 145, 230,50,'auto');
            }
        }

        return $fpdi->Output($outputFilePath, 'F');
    }
}
