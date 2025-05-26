<?php

namespace App\Http\Controllers\Admin;

use App\HighCourt;
use App\Http\Controllers\Controller;
use App\Payment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use PDF;
use setasign\Fpdi\Fpdi;

class PrintHighCourtController extends Controller
{
    public function printBankVoucher(Request $request)
    {
        $application = HighCourt::find($request->high_court_id);
        $payments = Payment::where('high_court_id', $application->id)->where('is_voucher_print', 1)->get();

        view()->share([
            'application' => $application,
            'payments' => $payments,
        ]);

        if ($request->has('download')) {
            $pdf = PDF::loadView('admin.high-court.prints.payment-vouchers');
            $pdf->setPaper('A4', 'landscape');
            return $pdf->stream('HC-HBL-VOUCHER-' . $application->id . '.pdf', array("Attachment" => false));
        }

        return back()->with('error', 'No Payment To Download');
    }

    public function printFormHc($id)
    {
        $application = HighCourt::find($id);
        $payments = Payment::where('user_id', $application->user_id)->where('application_type', 2)->get();

        view()->share([
            'application' => $application,
            'payments' => $payments,
        ]);

        $pdf = PDF::loadView('admin.high-court.prints.form-hc');
        $pdf->setPaper('Legal', 'portrait');
        return $pdf->stream('form-hc' . $application->id . '.pdf', array("Attachment" => false));

        return view('admin.high-court.prints.form-hc');
    }

    public function printShortDetail(Request $request)
    {
        try {

            view()->share([
                'data' => highCourtShortDetail($request->id, $request->type),
            ]);

            $pdf = PDF::loadView('admin.high-court.prints.short-detail');
            $pdf->setPaper('legal', 'potrait');
            return $pdf->stream('hc-short-detail-' . Carbon::now() . '.pdf', array("Attachment" => false));
        } catch (\Throwable $th) {
            throw $th;
            abort(403);
        }
    }

    public function printAdvocateCertificate($id)
    {
        $application = HighCourt::find($id);
        if (!getHcAdvocateCertificateStatus($application)) {
            abort(403);
        }

        try {
            $filePath = public_path("admin/files/advocate-certificate-hc.pdf");
            $outputFilePath = storage_path("app/public/hc-advocate-certificates/" . $application->id . ".pdf");
            $this->fill_pdf_file($filePath, $outputFilePath, $id);
            return response()->file($outputFilePath);
        } catch (\Throwable $th) {
            abort(403,$th->getMessage());
        }
    }

    private function fill_pdf_file($file, $outputFilePath, $id)
    {
        $application = HighCourt::find($id);

        $hc_date_jS = Carbon::parse($application->enr_date_hc)->format('jS');
        $hc_date_F = Carbon::parse($application->enr_date_hc)->format('F Y');
        $hc_date_ymd = Carbon::parse($application->enr_date_hc)->format('Y-m-d');
        $voter_member = strtoupper($application->user->father_name) . ' of ' . strtoupper(getVoterMemberName($application->voter_member_hc));

        $vc = "";
        //$vc = strtoupper('Kamran Bashir Mughal');
        $sctry = "";
        //$sctry = strtoupper('Rafaqat Ali Sohal');

        $laywer_name = isset($application->lawyer_name) ? strtoupper($application->lawyer_name) : '';

        $fpdi = new FPDI;
        $count = $fpdi->setSourceFile($file);

        $vcImage = null;
        $sctryImage = null;

        $memberVC = getMemberForCertificate($hc_date_ymd,"Vice Chairman");
        if ($memberVC != null) {
            $vc = $memberVC->name ?? $vc;
            $vcImage =  storage_path("app/public/". $memberVC->signature_url);
            Log::info("vcImage");
            Log::info($vcImage);
        }
        $memberSctry = getMemberForCertificate($hc_date_ymd, "Secretary");
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

            $fpdi->Text(161, 18, $application->license_no_hc);

            $fpdi->Text(70, 95, $laywer_name);
            $fpdi->Text(85, 108, $voter_member);

            // $fpdi->Text(50, 121, $hc_date_jS);
            // $fpdi->Text(140, 121, $hc_date_F);

            $fpdi->Text(50, 210, $hc_date_jS);
            $fpdi->Text(140, 210, $hc_date_F);

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
