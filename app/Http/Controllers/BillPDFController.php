<?php

namespace App\Http\Controllers;

use App\Models\Bill;
use App\Models\IllnessNotification;
use Illuminate\Http\Request;
use Spatie\Browsershot\Browsershot;

class BillPDFController extends Controller
{
    public function generatePDF(Bill $bill)
    {
        // Re-fetch the latest data
        $bill = $bill->fresh();

        // Render the view to HTML
        $html = view('bill-pdf-layout', compact('bill'))->render();

        // Define a unique PDF file path
        $pdfFilePath = storage_path('app/public/pdfFiles/ITSPBill.pdf');

        // Generate the PDF
        Browsershot::html($html)
            ->noSandbox() // Ensure it works on environments without sandbox permissions
            ->showBackground() // Render the background styles
            ->margins(0, 0, 0, 0) // Set the margins (top, right, bottom, left)
            ->savePdf($pdfFilePath); // Save the PDF to the defined path

        // Prepare the file name for download
//        $date = date('d.m.Y', strtotime($bill->date));
//        $firstName = $bill->customer()->first_name;
//        $lastName = $bill->customer()->last_name;
//        $id = $bill->customer()->id;
//        $customer_number = $bill->customer()->customer_number;

//        $fileName = "KD Nr. {$customer_number} {$firstName} {$lastName} {$date}.pdf";
        $fileName = "KD Nr.pdf";

        // Return the PDF as a download response
        return response()->download($pdfFilePath, $fileName, [
            'Content-Type' => 'application/pdf',
        ])->deleteFileAfterSend(true); // Optionally delete the file after it's sent
    }

}
