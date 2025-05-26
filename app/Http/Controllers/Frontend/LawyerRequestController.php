<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\LawyerRequest;
use App\Payment;
use PDF;
use setasign\Fpdi\Fpdi;

class LawyerRequestController extends Controller
{
    public function index()
    {
        return view('frontend.lawyer-requests.index');
    }

    public function create()
    {
        return view('frontend.lawyer-requests.create');
    }

    public function show($id)
    {
        return view('frontend.lawyer-requests.show', ['id' => $id]);
    }

    public function voucher($id)
    {
        $lawyer_request = LawyerRequest::find($id);
        $lawyer_request_payment = Payment::where('lawyer_request_id', $lawyer_request->id)->first();

        view()->share([
            'lawyer_request' => $lawyer_request,
            'lawyer_request_payment' => $lawyer_request_payment,
        ]);

        $pdf = PDF::loadView('frontend.lawyer-requests.voucher');
        $pdf->setPaper('A4', 'landscape');
        return $pdf->stream('HBL-' . $lawyer_request->id . '.pdf', array("Attachment" => false));

        return view('frontend.lawyer-requests.voucher');
    }

    public function generate($id)
    {
        try {
            $lawyer_request = LawyerRequest::find($id);
            $folder_id = $lawyer_request->getFirstMedia('lawyer_request_file')->id;
            $file_name = $lawyer_request->getFirstMedia('lawyer_request_file')->file_name;
            $filePath = storage_path("app/public/" . $folder_id . "/" . $file_name);
            $outputFilePath = storage_path("app/public/lawyer-request-output/" . $lawyer_request->id . ".pdf");
            $this->fill_pdf_file($filePath, $outputFilePath, $id);
            return response()->file($outputFilePath);
        } catch (\Throwable $th) {
            abort(403);
        }
    }

    public function fill_pdf_file($file, $outputFilePath, $id)
    {
        $lawyer_request = LawyerRequest::find($id);

        $fpdi = new FPDI;
        $count = $fpdi->setSourceFile($file);

        for ($i = 1; $i <= $count; $i++) {
            $template = $fpdi->importPage($i);
            $size = $fpdi->getTemplateSize($template);
            $fpdi->AddPage($size['orientation'], array($size['width'], $size['height']));
            $fpdi->useTemplate($template);

            $fpdi->SetFont("Courier", "B", 14);
            $fpdi->SetTextColor(0, 0, 0);

            $fpdi->Text(40, 56, $lawyer_request->id);
            $fpdi->Text(165, 56, getDateFormat($lawyer_request->created_at));

            $fpdi->Image("https://portal.pbbarcouncil.com/public/admin/images/certificate-letterhead.png", 15, 0);
        }

        return $fpdi->Output($outputFilePath, 'F');
    }
}
