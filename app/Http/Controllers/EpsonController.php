<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Mike42\Escpos\CapabilityProfile;
use Mike42\Escpos\Printer;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;

class EpsonController extends Controller
{
    public function printTest()
    {
        try {
            $profile = CapabilityProfile::load("simple");

            // Use the exact shared name of your printer from Windows
            $connector = new WindowsPrintConnector("smb://localhost/EPSON88");

            // $connector = new WindowsPrintConnector("0001");
            // $connector = new NetworkPrintConnector("10.0.0.50", 9100);


            $printer = new Printer($connector, $profile);

            $printer->text("Test receipt from Laravel on Windows 11\n");
            $printer->cut();
            $printer->close();

            // /* Print some bold text */
            // $printer->setEmphasis(true);
            // $printer->text("FOO CORP Ltd.\n");
            // $printer->setEmphasis(false);
            // $printer->feed();
            // $printer->text("Receipt for whatever\n");
            // $printer->feed(4);

            // /* Bar-code at the end */
            // $printer->setJustification(Printer::JUSTIFY_CENTER);
            // $printer->barcode("987654321");
            // $printer->cut();

            return "Print job sent successfully!";
        } catch (\Exception $e) {
            return "Print failed: " . $e->getMessage();
        }
    }
}
