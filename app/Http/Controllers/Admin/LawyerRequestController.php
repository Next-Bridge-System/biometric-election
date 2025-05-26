<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\LawyerRequest;
use setasign\Fpdi\Fpdi;

class LawyerRequestController extends Controller
{
    public function subCategoriesIndex()
    {
        return view('admin.lawyer-requests.lawyer-request-sub-category-index');
    }

    public function index()
    {
        return view('admin.lawyer-requests.index');
    }

    public function show($lawyer_request_id)
    {
        return view('admin.lawyer-requests.show', [
            'lawyer_request_id' => $lawyer_request_id
        ]);
    }

    public function generate($id)
    {
        try {
            $lawyer_request = LawyerRequest::find($id);
            if ($lawyer_request->approved != 1) {
                abort(403);
            }
            $folder_id = $lawyer_request->getFirstMedia('lawyer_request_file')->id;
            $file_name = $lawyer_request->getFirstMedia('lawyer_request_file')->file_name;
            $filePath = storage_path("app/public/" . $folder_id . "/" . $file_name);
            $outputFilePath = storage_path("app/public/lawyer-request-output/" . $lawyer_request->id . ".pdf");
            $this->fill_pdf_file($filePath, $outputFilePath, $id);
            return response()->file($outputFilePath);
        } catch (\Throwable $th) {
            abort(403, $th->getMessage());
        }
    }

    public function fill_pdf_file($file, $outputFilePath, $id)
    {
        $lawyer_request = LawyerRequest::find($id);

        $FPDI = new FPDI;
        $count = $FPDI->setSourceFile($file);

        $secretary = getMemberForCertificate($lawyer_request->created_at, "Secretary");        
        $secretary_image = storage_path("app/public/". $secretary->signature_url);

        for ($i = 1; $i <= $count; $i++) {
            $template = $FPDI->importPage($i);
            $size = $FPDI->getTemplateSize($template);
            $FPDI->AddPage($size['orientation'], array($size['width'], $size['height']));
            $FPDI->useTemplate($template);

            $FPDI->SetFont("Courier", "B", 14);
            $FPDI->SetTextColor(0, 0, 0);

            $FPDI->Text(40, 56, $lawyer_request->id);
            $FPDI->Text(165, 56, getDateFormat($lawyer_request->created_at));

            if ($secretary_image != null) {
                $FPDI->Image($secretary_image, 150, 215, 50, 'auto');
            }

            $FPDI->Image("https://portal.pbbarcouncil.com/public/admin/images/certificate-letterhead.png", 15, 0);
        }

        return $FPDI->Output($outputFilePath, 'F');
    }
}
