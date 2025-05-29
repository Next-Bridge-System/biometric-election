<?php

namespace App\Http\Controllers;

use Mike42\Escpos\CapabilityProfile;
use Mike42\Escpos\Printer;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;

class PrintController extends Controller
{
    public function printTest()
    {
        try {
            $profile = CapabilityProfile::load("simple");

            // Use the exact shared name of your printer from Windows
            $connector = new WindowsPrintConnector("EPSON_TM_T88V");
            $printer = new Printer($connector, $profile);

            // $printer->text("Test receipt from Laravel on Windows 11\n");
            // $printer->cut();
            // $printer->close();

            $printer->setJustification(Printer::JUSTIFY_CENTER);
            $printer->setTextSize(2, 2);
            $printer->text("FOO CORP Ltd.\n");
            $printer->setTextSize(1, 1);
            $printer->text("123 Market Street\n");
            $printer->text("Cityville, CV 12345\n");
            $printer->text("Tel: (123) 456-7890\n");
            $printer->feed();

            // Receipt title
            $printer->setEmphasis(true);
            $printer->text("SALES RECEIPT\n");
            $printer->setEmphasis(false);
            $printer->feed();

            // Items
            $printer->setJustification(Printer::JUSTIFY_LEFT);
            $printer->text("Qty  Item           Price   Total\n");
            $printer->text("-------------------------------\n");
            $printer->text("1    Widget A       9.99    9.99\n");
            $printer->text("2    Widget B       4.50    9.00\n");
            $printer->text("1    Widget C       5.00    5.00\n");
            $printer->text("-------------------------------\n");

            // Total
            $printer->setEmphasis(true);
            $printer->text("TOTAL                     23.99\n");
            $printer->setEmphasis(false);
            $printer->feed(2);

            // Barcode
            $printer->setJustification(Printer::JUSTIFY_CENTER);
            $printer->text("Order #123456\n");
            // $printer->barcode("123456", Printer::BARCODE_CODE39);
            $printer->feed(2);

            // Cut
            $printer->cut();
            $printer->close();

            return "Print job sent successfully!";
        } catch (\Exception $e) {
            return "Print failed: " . $e->getMessage();
        }
    }
}
